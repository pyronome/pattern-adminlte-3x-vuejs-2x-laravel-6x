/**
 * jQuery Menu Editor
 * @author David Ticona Saravia https://github.com/davicotico
 * @version 1.0.0
 * */
 (function ($){
    
    /**
     * @desc jQuery plugin to sort html list also the tree structures
     * @version 1.4.0
     * @author Vladimír Čamaj
     * @license MIT
     * @desc jQuery plugin
     * @param options
     * @returns this to unsure chaining
     */
    $.fn.sortableLists = function( options )
    {
        // Local variables. This scope is available for all the functions in this closure.
        var jQBody = $( 'body' ).css( 'position', 'relative' ),

            defaults = {
                currElClass: '',
                placeholderClass: '',
                placeholderCss: {
                    'position': 'relative',
                    'padding': 0
                },
                hintClass: '',
                hintCss: {
                    'display': 'none',
                    'position': 'relative',
                    'padding': 0
                },
                hintWrapperClass: '',
                hintWrapperCss: { /* Description is below the defaults in this var section */ },
                baseClass: '',
                baseCss: {
                    'position': 'absolute',
                    'top': 0 - parseInt( jQBody.css( 'margin-top' ) ),
                    'left': 0 - parseInt( jQBody.css( 'margin-left' ) ),
                    'margin': 0,
                    'padding': 0,
                    'z-index': 2500
                },
                opener: {
                    active: false,
                    open: '',
                    close: '',
                    openerCss: {
                        'float': 'left',
                        'display': 'inline-block',
                        'background-position': 'center center',
                        'background-repeat': 'no-repeat'
                    },
                    openerClass: ''
                },
                listSelector: 'ul',
                listsClass: '', // Used for hintWrapper and baseElement
                listsCss: {},
                insertZone: 50,
                insertZonePlus: false,
                scroll: 20,
                ignoreClass: '',
                isAllowed: function( cEl, hint, target ) { return true; },  // Params: current el., hint el.
                onDragStart: function( e, cEl ) { return true; },  // Params: e jQ. event obj., current el.
                onChange: function( cEl ) { return true; },  // Params: current el.
                complete: function( cEl ) { return true; }  // Params: current el.
            },

            setting = $.extend( true, {}, defaults, options ),

            // base element from which is counted position of draged element
            base = $( '<' + setting.listSelector + ' />' )
                .prependTo( jQBody )
                .attr( 'id', 'sortableListsBase' )
                .css( setting.baseCss )
                .addClass( setting.listsClass + ' ' + setting.baseClass ),

            // placeholder != state.placeholderNode
            // placeholder is document fragment and state.placeholderNode is document node
            placeholder = $( '<li />' )
                .attr( 'id', 'sortableListsPlaceholder' )
                .css( setting.placeholderCss )
                .addClass( setting.placeholderClass ),

            // hint is document fragment
            hint = $( '<li />' )
                .attr( 'id', 'sortableListsHint' )
                .css( setting.hintCss )
                .addClass( setting.hintClass ),

            // Is document fragment used as wrapper if hint is inserted to the empty li
            hintWrapper = $( '<' + setting.listSelector + ' />' )
                .attr( 'id', 'sortableListsHintWrapper' )
                .addClass( setting.listsClass + ' ' + setting.hintWrapperClass )
                .css( setting.listsCss )
                .css( setting.hintWrapperCss ),

            // Is +/- ikon to open/close nested lists
            opener = $( '<span />' )
                .addClass( 'openerStyle sortableListsOpener ' + setting.opener.openerClass )
                .css( setting.opener.openerCss )
                .on( 'mousedown touchstart', function( e )
                {
                    var li = $( this ).closest( 'li' );

                    if ( li.hasClass( 'sortableListsClosed' ) )
                    {
                        open( li );
                    }
                    else
                    {
                        close( li );
                    }

                    return false; // Prevent default
                } );

        if ( setting.opener.as == 'class' )
        {
            opener.addClass( setting.opener.close );
        }
        else if ( setting.opener.as == 'html' )
        {
            opener.html( setting.opener.close );
        }
        else
        {
            opener.css( 'background-image', 'url(' + setting.opener.close + ')' );
            console.error( 'jQuerySortableLists opener as background image is deprecated. In version 2.0.0 it will be removed. Use html instead please.' );
        }

        // Container with all actual elements and parameters
        var state = {
            isDragged: false,
            isRelEFP: null,  // How browser counts elementFromPoint() position (relative to window/document)
            oEl: null, // overElement is element which returns elementFromPoint() method
            rootEl: null,
            cEl: null, // currentElement is currently dragged element
            upScroll: false,
            downScroll: false,
            pX: 0,
            pY: 0,
            cX: 0,
            cY: 0,
            isAllowed: true, // The function is defined in setting
            e: { pageX: 0, pageY: 0, clientX: 0, clientY: 0 }, // TODO: unused??
            doc: $( document ),
            win: $( window )
        };

        if ( setting.opener.active )
        {
            if ( ! setting.opener.open ) throw 'Opener.open value is not defined. It should be valid url, html or css class.';
            if ( ! setting.opener.close ) throw 'Opener.close value is not defined. It should be valid url, html or css class.';

            $( this ).find( 'li' ).each( function()
            {
                var li = $( this );

                if ( li.children( setting.listSelector ).length )
                {
                    opener.clone( true ).prependTo( li.children( 'div' ).first() );

                    if ( ! li.hasClass( 'sortableListsOpen' ) )
                    {
                        close( li );
                    }
                    else
                    {
                        open( li );
                    }
                }
            } );
        }

        // Return this ensures chaining
        /*return this.on( 'mousedown touchstart', function( e )
            {
                var target = $( e.target );

                if ( state.isDragged !== false || ( setting.ignoreClass && target.hasClass( setting.ignoreClass ) ) ) return; // setting.ignoreClass is checked cause hasClass('') returns true

                // Solves selection/range highlighting
                e.preventDefault();

                if ( e.type === 'touchstart' )
                {
                    setTouchEvent( e );
                }

                // El must be li in jQuery object
                var el = target.closest( 'li' ),
                    rEl = $( this );

                // Check if el is not empty
                if ( el[ 0 ] )
                {
                    setting.onDragStart( e, el );
                    startDrag( e, el, rEl );
                }
            }
        );
*/
        /**
         * @desc Binds events dragging and endDrag, sets some init. values
         * @param e event obj.
         * @param el curr. dragged element
         * @param rEl root element
         */
        function startDrag( e, el, rEl )
        {
            state.isDragged = true;

            var elMT = parseInt( el.css( 'margin-top' ) ), // parseInt is necesary cause value has px at the end
                elMB = parseInt( el.css( 'margin-bottom' ) ),
                elML = parseInt( el.css( 'margin-left' ) ),
                elMR = parseInt( el.css( 'margin-right' ) ),
                elXY = el.offset(),
                elIH = el.innerHeight();

            state.rootEl = {
                el: rEl,
                offset: rEl.offset(),
                rootElClass: rEl.attr( 'class' )
            };

            state.cEl = {
                el: el,
                mT: elMT, mL: elML, mB: elMB, mR: elMR,
                offset: elXY
            };

            state.cEl.xyOffsetDiff = { X: e.pageX - state.cEl.offset.left, Y: e.pageY - state.cEl.offset.top };
            state.cEl.el.addClass( 'sortableListsCurrent' + ' ' + setting.currElClass );

            el.before( placeholder );  // Now document has node placeholder

            var placeholderNode = state.placeholderNode = $( '#sortableListsPlaceholder' );  // jQuery object && document node

            el.css( {
                'width': el.width(),
                'position': 'absolute',
                'top': elXY.top - elMT,
                'left': elXY.left - elML
            } ).prependTo( base );

            placeholderNode.css( {
                'display': 'block',
                'height': elIH
            } );

            hint.css( 'height', elIH );

            state.doc
                .on( 'mousemove touchmove', dragging )
                .on( 'mouseup touchend touchcancel', endDrag );

        }

        /**
         * @desc Start dragging
         * @param e event obj.
         */
        function dragging( e )
        {
            if ( state.isDragged )
            {
                var cEl = state.cEl,
                    doc = state.doc,
                    win = state.win;

                if ( e.type === 'touchmove' )
                {
                    setTouchEvent( e );
                }

                // event triggered by trigger() from setInterval does not have XY properties
                if ( ! e.pageX )
                {
                    setEventPos( e );
                }

                // Scrolling up
                if ( doc.scrollTop() > state.rootEl.offset.top - 10 && e.clientY < 50 )
                {
                    if ( ! state.upScroll ) // Has to be here after cond. e.clientY < 50 cause else unsets the interval
                    {
                        setScrollUp( e );
                    }
                    else
                    {
                        e.pageY = e.pageY - setting.scroll;
                        $( 'html, body' ).each( function( i )
                        {
                            $( this ).scrollTop( $( this ).scrollTop() - setting.scroll );
                        } );
                        setCursorPos( e );
                    }
                }
                // Scrolling down
                else if ( doc.scrollTop() + win.height() < state.rootEl.offset.top + state.rootEl.el.outerHeight( false ) + 10 && win.height() - e.clientY < 50 )
                {
                    if ( ! state.downScroll )
                    {
                        setScrollDown( e );
                    }
                    else
                    {
                        e.pageY = e.pageY + setting.scroll;
                        $( 'html, body' ).each( function( i )
                        {
                            $( this ).scrollTop( $( this ).scrollTop() + setting.scroll );
                        } );
                        setCursorPos( e );
                    }
                }
                else
                {
                    scrollStop( state );
                }

                // Script needs to know old oEl
                state.oElOld = state.oEl;

                cEl.el[ 0 ].style.visibility = 'hidden';  // This is important for the next row
                state.oEl = oEl = elFromPoint( e.pageX, e.pageY );
                cEl.el[ 0 ].style.visibility = 'visible';

                showHint( e, state );

                setCElPos( e, state );

            }
        }

        /**
         * @desc endDrag unbinds events mousemove/mouseup and removes redundant elements
         * @param e
         */
        function endDrag( e )
        {
            var cEl = state.cEl,
                hintNode = $( '#sortableListsHint', state.rootEl.el ),
                hintStyle = hint[ 0 ].style,
                targetEl = null, // hintNode/placeholderNode
                isHintTarget = false, // if cEl will be placed to the hintNode
                hintWrapperNode = $( '#sortableListsHintWrapper' );

            if ( e.type === 'touchend' || e.type === 'touchcancel' )
            {
                setTouchEvent( e );
            }

            if ( hintStyle.display == 'block' && hintNode.length && state.isAllowed )
            {
                targetEl = hintNode;
                isHintTarget = true;
            }
            else
            {
                targetEl = state.placeholderNode;
                isHintTarget = false;
            }

            offset = targetEl.offset();

            cEl.el.animate( { left: offset.left - state.cEl.mL, top: offset.top - state.cEl.mT }, 250,
                function()  // complete callback
                {
                    tidyCurrEl( cEl );

                    targetEl.after( cEl.el[ 0 ] );
                    targetEl[ 0 ].style.display = 'none';
                    hintStyle.display = 'none';
                    // This have to be document node, not hint as a part of documentFragment.
                    hintNode.remove();

                    hintWrapperNode
                        .removeAttr( 'id' )
                        .removeClass( setting.hintWrapperClass );

                    if ( hintWrapperNode.length )
                    {
                        //hintWrapperNode.prev( 'div' ).append( opener.clone( true ) ); // original
                        hintWrapperNode.prev( 'div' ).prepend( opener.clone( true ) ); //david
                    }

                    // Directly removed placeholder looks bad. It jumps up if the hint is below.
                    if ( isHintTarget )
                    {
                        state.placeholderNode.slideUp( 150, function()
                        {
                            state.placeholderNode.remove();
                            tidyEmptyLists();
                            setting.onChange( cEl.el );
                            setting.complete( cEl.el ); // Have to be here cause is necessary to remove placeholder before complete call.
                            state.isDragged = false;
                        } );
                    }
                    else
                    {
                        state.placeholderNode.remove();
                        tidyEmptyLists();
                        setting.complete( cEl.el );
                        state.isDragged = false;
                    }

                } );

            scrollStop( state );

            state.doc
                .unbind( "mousemove touchmove", dragging )
                .unbind( "mouseup touchend touchcancel", endDrag );


        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        //////// Helpers /////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        //////// Scroll handlers /////////////////////////////////////////////////////////////////////////////

        /**
         * @desc Ensures autoscroll up.
         * @param e
         * @return No value
         */
        function setScrollUp( e )
        {
            if ( state.upScroll ) return;

            state.upScroll = setInterval( function()
            {
                state.doc.trigger( 'mousemove' );
            }, 50 );

        }

        /**
         * @desc Ensures autoscroll down.
         * @param e
         * @return No value
         */
        function setScrollDown( e )
        {
            if ( state.downScroll ) return;

            state.downScroll = setInterval( function()
            {
                state.doc.trigger( 'mousemove' );
            }, 50 );

        }

        /**
         * @desc This properties are used when setScrollUp()/Down() calls trigger('mousemove'), cause trigger() produce event object without pageY/Y and clientX/Y.
         * @param e
         * @return No value
         */
        function setCursorPos( e )
        {
            state.pY = e.pageY;
            state.pX = e.pageX;
            state.cY = e.clientY;
            state.cX = e.clientX;
        }

        /**
         * @desc Necessary while scrolling, cause trigger('mousemove') does not set cursor XY values in event object
         * @param e
         * @return No value
         */
        function setEventPos( e )
        {
            e.pageY = state.pY;
            e.pageX = state.pX;
            e.clientY = state.cY;
            e.clientX = state.cX;
        }

        /**
         * @desc Stops scrolling and sets variables
         * @param state
         * @return No value
         */
        function scrollStop( state )
        {
            clearInterval( state.upScroll );
            clearInterval( state.downScroll );
            // clearInterval have to be before upScroll/downScroll is set to false
            state.upScroll = state.downScroll = false;
        }

        /////// End of Scroll handlers //////////////////////////////////////////////////////////////
        /////// Current element handlers //////////////////////////////////////////////////////////////

        /**
         * Sets the e.page/e.screen properties
         * @param e
         */
        function setTouchEvent( e )
        {
            e.pageX = e.originalEvent.changedTouches[ 0 ].pageX;
            e.pageY = e.originalEvent.changedTouches[ 0 ].pageY;
            e.screenX = e.originalEvent.changedTouches[ 0 ].screenX;
            e.screenY = e.originalEvent.changedTouches[ 0 ].screenY;
        }

        /**
         * @desc Sets the position of dragged element
         * @param e event object
         * @param state state object
         * @return No value
         */
        function setCElPos( e, state )
        {
            var cEl = state.cEl;

            cEl.el.css( {
                'top': e.pageY - cEl.xyOffsetDiff.Y - cEl.mT,
                'left': e.pageX - cEl.xyOffsetDiff.X - cEl.mL
            } )

        }

        /**
         * @desc Return elementFromPoint() result as jQuery object
         * @param x e.pageX
         * @param y e.pageY
         * @return null|jQuery object
         */
        function elFromPoint( x, y )
        {
            if ( ! document.elementFromPoint ) return null;

            // FF/IE/CH needs coordinates relative to the window, unlike
            // Opera/Safari which needs absolute coordinates of document in elementFromPoint()
            var isRelEFP = state.isRelEFP;

            // isRelative === null means it is not checked yet
            if ( isRelEFP === null )
            {
                var s, res;
                if ( (s = state.doc.scrollTop()) > 0 )
                {
                    isRelEFP = ( (res = document.elementFromPoint( 0, s + $( window ).height() - 1 ) ) == null
                    || res.tagName.toUpperCase() == 'HTML');  // IE8 returns html
                }
                if ( (s = state.doc.scrollLeft()) > 0 )
                {
                    isRelEFP = ( (res = document.elementFromPoint( s + $( window ).width() - 1, 0 ) ) == null
                    || res.tagName.toUpperCase() == 'HTML');  // IE8 returns html
                }
            }

            if ( isRelEFP )
            {
                x -= state.doc.scrollLeft();
                y -= state.doc.scrollTop();
            }

            // Returns jQuery object
            var el = $( document.elementFromPoint( x, y ) );

            if ( ! state.rootEl.el.find( el ).length ) // el is outside the rootEl
            {
                return null;
            }
            else if ( el.is( '#sortableListsPlaceholder' ) || el.is( '#sortableListsHint' ) ) // el is #placeholder/#hint
            {
                return null;
            }
            else if ( ! el.is( 'li' ) ) // el is ul or div or something else in li elem.
            {
                el = el.closest( 'li' );
                return el[ 0 ] ? el : null;
            }
            else if ( el.is( 'li' ) ) // el is most wanted li
            {
                return el;
            }

        }

        //////// End of current element handlers //////////////////////////////////////////////////////
        //////// Show hint handlers //////////////////////////////////////////////////////

        /**
         * @desc Shows or hides or does not show hint element
         * @param e event
         * @param state
         * @return No value
         */
        function showHint( e, state )
        {
            var oEl = state.oEl;

            // If oEl is null or if this is the first call in dragging
            if ( ! oEl || ! state.oElOld )  return;

            var oElH = oEl.outerHeight( false ),
                relY = e.pageY - oEl.offset().top;

            if ( setting.insertZonePlus )
            {
                if ( 14 > relY )  // Inserting on top
                {
                    showOnTopPlus( e, oEl, 7 > relY );  // Last bool param express if hint insert outside/inside
                }
                else if ( oElH - 14 < relY )  // Inserting on bottom
                {
                    showOnBottomPlus( e, oEl, oElH - 7 < relY );
                }
            }
            else
            {
                if ( 5 > relY )  // Inserting on top
                {
                    showOnTop( e, oEl );
                }
                else if ( oElH - 5 < relY )  // Inserting on bottom
                {
                    showOnBottom( e, oEl );
                }
            }
        }

        /**
         * @desc Called from showHint method. Displays or hides hint element
         * @param e event
         * @param oEl oElement
         * @return No value
         */
        function showOnTop( e, oEl )
        {
            if ( $( '#sortableListsHintWrapper', state.rootEl.el ).length )
            {
                hint.unwrap();  // If hint is wrapped by ul/ol #sortableListsHintWrapper
            }

            // Hint outside the oEl
            if ( e.pageX - oEl.offset().left < setting.insertZone )
            {
                // Ensure display:none if hint will be next to the placeholder
                if ( oEl.prev( '#sortableListsPlaceholder' ).length )
                {
                    hint.css( 'display', 'none' );
                    return;
                }
                oEl.before( hint );
            }
            // Hint inside the oEl
            else
            {
                var children = oEl.children(),
                    list = oEl.children( setting.listSelector ).first();

                if ( list.children().first().is( '#sortableListsPlaceholder' ) )
                {
                    hint.css( 'display', 'none' );
                    return;
                }

                // Find out if is necessary to wrap hint by hintWrapper
                if ( ! list.length )
                {
                    children.first().after( hint );
                    hint.wrap( hintWrapper );
                }
                else
                {
                    list.prepend( hint );
                }

                if ( state.oEl )
                {
                    open( oEl ); // TODO:animation??? .children('ul,ol').css('display', 'block');
                }

            }

            hint.css( 'display', 'block' );
            // Ensures posible formating of elements. Second call is in the endDrag method.
            state.isAllowed = setting.isAllowed( state.cEl.el, hint, hint.parents( 'li' ).first() );

        }

        /**
         * @desc Called from showHint method. Displays or hides hint element
         * @param e event
         * @param oEl oElement
         * @param outside bool
         * @return No value
         */
        function showOnTopPlus( e, oEl, outside )
        {
            if ( $( '#sortableListsHintWrapper', state.rootEl.el ).length )
            {
                hint.unwrap();  // If hint is wrapped by ul/ol #sortableListsHintWrapper
            }

            // Hint inside the oEl
            if ( ! outside && e.pageX - oEl.offset().left > setting.insertZone )
            {
                var children = oEl.children(),
                    list = oEl.children( setting.listSelector ).first();

                if ( list.children().first().is( '#sortableListsPlaceholder' ) )
                {
                    hint.css( 'display', 'none' );
                    return;
                }

                // Find out if is necessary to wrap hint by hintWrapper
                if ( ! list.length )
                {
                    children.first().after( hint );
                    hint.wrap( hintWrapper );
                }
                else
                {
                    list.prepend( hint );
                }

                if ( state.oEl )
                {
                    open( oEl ); // TODO:animation??? .children('ul,ol').css('display', 'block');
                }
            }
            // Hint outside the oEl
            else
            {
                // Ensure display:none if hint will be next to the placeholder
                if ( oEl.prev( '#sortableListsPlaceholder' ).length )
                {
                    hint.css( 'display', 'none' );
                    return;
                }
                oEl.before( hint );

            }

            hint.css( 'display', 'block' );
            // Ensures posible formating of elements. Second call is in the endDrag method.
            state.isAllowed = setting.isAllowed( state.cEl.el, hint, hint.parents( 'li' ).first() );

        }

        /**
         * @desc Called from showHint function. Displays or hides hint element.
         * @param e event
         * @param oEl oElement
         * @return No value
         */
        function showOnBottom( e, oEl )
        {
            if ( $( '#sortableListsHintWrapper', state.rootEl.el ).length )
            {
                hint.unwrap();  // If hint is wrapped by ul/ol sortableListsHintWrapper
            }

            // Hint outside the oEl
            if ( e.pageX - oEl.offset().left < setting.insertZone )
            {
                // Ensure display:none if hint will be next to the placeholder
                if ( oEl.next( '#sortableListsPlaceholder' ).length )
                {
                    hint.css( 'display', 'none' );
                    return;
                }
                oEl.after( hint );
            }
            // Hint inside the oEl
            else
            {
                var children = oEl.children(),
                    list = oEl.children( setting.listSelector ).last();  // ul/ol || empty jQuery obj

                if ( list.children().last().is( '#sortableListsPlaceholder' ) )
                {
                    hint.css( 'display', 'none' );
                    return;
                }

                // Find out if is necessary to wrap hint by hintWrapper
                if ( list.length )
                {
                    children.last().append( hint );
                }
                else
                {
                    oEl.append( hint );
                    hint.wrap( hintWrapper );
                }

                if ( state.oEl )
                {
                    open( oEl ); // TODO: animation???
                }

            }

            hint.css( 'display', 'block' );
            // Ensures posible formating of elements. Second call is in the endDrag method.
            state.isAllowed = setting.isAllowed( state.cEl.el, hint, hint.parents( 'li' ).first() );

        }

        /**
         * @desc Called from showHint function. Displays or hides hint element.
         * @param e event
         * @param oEl oElement
         * @param outside bool
         * @return No value
         */
        function showOnBottomPlus( e, oEl, outside )
        {
            if ( $( '#sortableListsHintWrapper', state.rootEl.el ).length )
            {
                hint.unwrap();  // If hint is wrapped by ul/ol sortableListsHintWrapper
            }

            // Hint inside the oEl
            if ( ! outside && e.pageX - oEl.offset().left > setting.insertZone )
            {
                var children = oEl.children(),
                    list = oEl.children( setting.listSelector ).last();  // ul/ol || empty jQuery obj

                if ( list.children().last().is( '#sortableListsPlaceholder' ) )
                {
                    hint.css( 'display', 'none' );
                    return;
                }

                // Find out if is necessary to wrap hint by hintWrapper
                if ( list.length )
                {
                    children.last().append( hint );
                }
                else
                {
                    oEl.append( hint );
                    hint.wrap( hintWrapper );
                }

                if ( state.oEl )
                {
                    open( oEl ); // TODO: animation???
                }

            }
            // Hint outside the oEl
            else
            {
                // Ensure display:none if hint will be next to the placeholder
                if ( oEl.next( '#sortableListsPlaceholder' ).length )
                {
                    hint.css( 'display', 'none' );
                    return;
                }
                oEl.after( hint );

            }

            hint.css( 'display', 'block' );
            // Ensures posible formating of elements. Second call is in the endDrag method.
            state.isAllowed = setting.isAllowed( state.cEl.el, hint, hint.parents( 'li' ).first() );

        }

        //////// End of show hint handlers ////////////////////////////////////////////////////
        //////// Open/close handlers //////////////////////////////////////////////////////////

        /**
         * @desc Handles opening nested lists
         * @param li
         */
        function open( li )
        {
            li.removeClass( 'sortableListsClosed' ).addClass( 'sortableListsOpen' );
            li.children( setting.listSelector ).css( 'display', 'block' );

            var opener = li.children( 'div' ).children( '.sortableListsOpener' ).first();

            if ( setting.opener.as == 'html' )
            {
                opener.html( setting.opener.close );
            }
            else if ( setting.opener.as == 'class' )
            {
                opener.addClass( setting.opener.close ).removeClass( setting.opener.open );
            }
            else
            {
                opener.css( 'background-image', 'url(' + setting.opener.close + ')' );
            }
        }

        /**
         * @desc Handles opening nested lists
         * @param li
         */
        function close( li )
        {
            li.removeClass( 'sortableListsOpen' ).addClass( 'sortableListsClosed' );
            li.children( setting.listSelector ).css( 'display', 'none' );

            var opener = li.children( 'div' ).children( '.sortableListsOpener' ).first();

            if ( setting.opener.as == 'html' )
            {
                opener.html( setting.opener.open );
            }
            else if ( setting.opener.as == 'class' )
            {
                opener.addClass( setting.opener.open ).removeClass( setting.opener.close );
            }
            else
            {
                opener.css( 'background-image', 'url(' + setting.opener.open + ')' );
            }

        }

        /////// Enf of open/close handlers //////////////////////////////////////////////

        /**
         * @desc Places the currEl to the target place
         * @param cEl
         */
        function tidyCurrEl( cEl )
        {
            var cElStyle = cEl.el[ 0 ].style;

            cEl.el.removeClass( setting.currElClass + ' ' + 'sortableListsCurrent' );
            cElStyle.top = '0';
            cElStyle.left = '0';
            cElStyle.position = 'relative';
            cElStyle.width = 'auto';

        }

        /**
         * @desc Removes empty lists and redundant openers
         */
        function tidyEmptyLists()
        {
            // Remove every empty ul/ol from root and also with .sortableListsOpener
            // hintWrapper can not be removed before the hint
            $( setting.listSelector, state.rootEl.el ).each( function( i )
                {
                    if ( ! $( this ).children().length )
                    {
                        $( this ).prev( 'div' ).children( '.sortableListsOpener' ).first().remove();
                        $( this ).remove();
                    }
                }
            );

        }

    };

    /** END PLUGIN sortableLists */

    /**
     * @desc Handles opening nested lists
     * @param setting
     */
    $.fn.iconOpen = function(setting){
        this.removeClass('sortableListsClosed').addClass('sortableListsOpen');
        this.children('ul').css('display', 'block');
        var opener = this.children('div').children('.sortableListsOpener').first();
        if (setting.opener.as === 'html'){
            opener.html(setting.opener.close);
        } else if (setting.opener.as === 'class') {
            opener.addClass(setting.opener.close).removeClass(setting.opener.open);
        }
    };
    /**
     * @desc Handles closing nested lists
     * @param setting
     */
    $.fn.iconClose = function(setting) {
        this.removeClass('sortableListsOpen').addClass('sortableListsClosed');
        this.children('ul').css('display', 'none');
        var opener = this.children('div').children('.sortableListsOpener').first();
        if (setting.opener.as === 'html') {
            opener.html(setting.opener.open);
        } else if (setting.opener.as === 'class') {
            opener.addClass(setting.opener.open).removeClass(setting.opener.close);
        }
    };
    
    /**
     * @author David Ticona Saravia
     * @desc Get the json from html list
     * @return {array} Array
     */
    $.fn.sortableListsToJson = function (){
        var arr = [];
        $(this).children('li').each(function () {
            var li = $(this);
            var object = {
                "system" : li.data("system"),
                "owner" : li.data("owner"),
                "is_owner" : li.data("is_owner"),
                "locked" : li.data("locked"),
                "editable" : li.data("editable"),
                "basekey" : li.data("basekey"),
                "__key" : li.data("__key"),
                "__parent" : li.data("__parent"),
                "content" : li.data("content"),
                "default_value" : li.data("default_value"),
                "enabled" : li.data("enabled"),
                "only_admins" : li.data("only_admins"),
                "file_types" : li.data("file_types"),
                "max" : li.data("max"),
                "min" : li.data("min"),
                "multiple" : li.data("multiple"),
                "option_titles" : li.data("option_titles"),
                "option_values" : li.data("option_values"),
                "required" : li.data("required"),
                "step" : li.data("step"),
                "title" : li.data("title"),
                "toggle_elements" : li.data("toggle_elements"),
                "type" : li.data("type"),
                "url" : li.data("url"),
                "value" : li.data("value"),
                "hint" : li.data("hint"),
                "description" : li.data("description"),
                "large_screen_size" : li.data("large_screen_size"),
                "medium_screen_size" : li.data("medium_screen_size"),
                "small_screen_size" : li.data("small_screen_size"),
                "max_selection" : li.data("max_selection"),
                "min_selection" : li.data("min_selection"),
                "expression" : li.data("expression"),
                "message" : li.data("message"),
            };

            arr.push(object);
            var ch = li.children('ul,ol').sortableListsToJson();
            if (ch.length > 0) {
                object.children = ch;
            } else {
                delete object.children;
            }
        });
        return arr;
    };
    /**
     * @description Update the buttons at the nested list (the main <ul>).
     * the buttons are: up, down, item in, item out
     * @param {int} depth 
     */
    $.fn.updateButtons = function (depth){
        var level = (typeof depth === 'undefined') ? 0 : depth;
        var removefirst = ['In'];
        var removelast = [];
        var removeUpDown = ['Up', 'Down'];
        if (level===0){
            removefirst.push('Out');
            removelast.push('Out');
            $(this).children('li').hideButtons(['Out']);
            $(this).children('li').hideButtons(removeUpDown);
        }

        $(this).children('li').each(function () {
            var $li = $(this);
            var $ul = $li.children('ul');
            if ($ul.length > 0) {
                $ul.updateButtons(level + 1);
            }
        });
        $(this).children('li:first').hideButtons(removefirst);
        $(this).children('li:last').hideButtons(removelast);
        $(this).children('li').hideButtons(removeUpDown);

        if (level== 1) {
            var inButtons = ['In'];
            $(this).children('li').hideButtons(inButtons);
            $(this).children('li').hideButtons(removeUpDown);
            $(this).parent('li').hideButtons(inButtons);
            $(this).parent('li').hideButtons(removeUpDown);
        }

    };
    /**
     * @description Hide the buttons at the item <li>
     * @param {Array} buttons 
     */
    $.fn.hideButtons = function(buttons){
        for(var i = 0; i<buttons.length; i++){
            $(this).find('.btn-group:first').children(".btn"+buttons[i]).hide();
        }
    };
}(jQuery));
/**
 * @version 1.0.0
 * @author David Ticona Saravia
 * @param {string} idSelector Attr ID
 * @param {object} options Options editor
 * */
function MenuEditor(idSelector, options) {
    var $main = $("#" + idSelector);
    var settings = {
        labelEdit: '<i class="fas fa-pen clickable"></i>',
        labelRemove: '<i class="fas fa-times clickable"></i>',
        textConfirmDelete: 'This item will be deleted. Are you sure?',
        listOptions: { 
            hintCss: { border: '1px dashed #13981D'}, 
            opener: {
                as: 'html',
                close: '<i class="fas fa-minus"></i>',
                open: '<i class="fas fa-plus"></i>',
                openerCss: {'margin-right': '10px', 'float': 'none'},
                openerClass: 'btn btn-success btn-sm',
            },
            placeholderCss: {'background-color': 'gray'},
            ignoreClass: 'clickable',
            listsClass: "pl-0",
            listsCss: {"padding-top": "10px"},
            complete: function (cEl) {
                MenuEditor.updateButtons($main);
                return true;
            }
        }
    };
    $.extend(true, settings, options);
    var itemEditing = null;
    var sortableReady = true;
    var $form = null;
    var $updateButton = null;
    var options = settings.listOptions;
    //sortable list plugin

    $main.sortableLists(settings.listOptions);
    /* EVENTS */
    $(document).on('click', '#ulConfigEditor .btnRemove', function (e) {
        e.preventDefault();

        var list = $(this).closest('ul');
        var li = $(this).closest('li');
        

        $("#buttonDeleteMenuItem").data("closestUL", list);
        $("#buttonDeleteMenuItem").data("closestLI", li);

        $("#deleteWarning").removeClass("d-none");
        $("#groupDeleteWarning").addClass("d-none");

        var title = $(li).data("title");
        var type = $(li).data("type");

        if (("group" == type) || ("selection_group" == type)) {
            if (li.children('ul').children("li").length > 0) {
                $("#deleteWarning").addClass("d-none");
                $("#groupDeleteWarning").removeClass("d-none");
            }
        }


        $("#modalMenuItemDelete").modal();
    });

    $(document).on('click', '#buttonDeleteMenuItem', function (e) {
        e.preventDefault();
        
        var list = $(this).data("closestUL");
        var li = $(this).data("closestLI");
        var exceptionKey = $(li).data("__key");
        $(li).remove();

        var isMainContainer = false;
        if (typeof list.attr('id') !== 'undefined') {
            isMainContainer = (list.attr('id').toString() === idSelector);
        }
        if ((!list.children().length) && (!isMainContainer)) {
            list.prev('div').children('.sortableListsOpener').first().remove();
            list.remove();
        }
        MenuEditor.updateButtons($main);
        $("#modalMenuItemDelete").modal('hide');

        document.getElementById("exception_key").value = exceptionKey;
        $("#exception_key").trigger("click");
    });

    $(document).on('click', '#ulConfigEditor .btnCopy', function (e) {
        e.preventDefault();
        copyItem($(this).closest('li'));

        /* $("#buttonAddMenuItem").hide();
        $("#buttonUpdateMenuItem").show();
        $("#modalMenuItem").modal(); */
    });

    $(document).on('click', '#ulConfigEditor .btnEdit', function (e) {
        e.preventDefault();
        itemEditing = $(this).closest('li');
        editItem(itemEditing);

        $("#buttonAddMenuItem").hide();
        $("#buttonUpdateMenuItem").show();
        $("#modalMenuItem").modal();
    });

    $(document).on('click', '#ulConfigEditor .btnUp', function (e) {
        e.preventDefault();
        var $li = $(this).closest('li');
        $li.prev('li').before($li);
        MenuEditor.updateButtons($main);
    });
    $(document).on('click', '#ulConfigEditor .btnDown', function (e) {
        e.preventDefault();
        var $li = $(this).closest('li');
        $li.next('li').after($li);
        MenuEditor.updateButtons($main);
    });
    $(document).on('click', '#ulConfigEditor .btnOut', function (e) {
        e.preventDefault();
        var list = $(this).closest('ul');
        var $li = $(this).closest('li');
        var $liParent = $li.closest('ul').closest('li');
        $liParent.after($li);
        if (list.children().length <= 0) {
            list.prev('div').children('.sortableListsOpener').first().remove();
            list.remove();

            if (1 == $liParent.data.__group) {
                $liParent.find('.btnMove').remove();
            }
        }
        MenuEditor.updateButtons($main);
    });
    $(document).on('click', '#ulConfigEditor .btnIn', function (e) {
        e.preventDefault();
        var $li = $(this).closest('li');
        var $prev = $li.prev('li');
        
        /* if (1 == $prev.data("__group")) {
            alert("You cannot add a submenu into group element!")
            return false;
        } */

        if ($prev.length > 0) {
            var $ul = $prev.children('ul');
            if ($ul.length > 0)
                $ul.append($li);
            else {
                var $ul = $('<ul>').addClass('pl-0').css('padding-top', '10px');
                $prev.append($ul);
                $ul.append($li);
                $prev.addClass('sortableListsOpen');
                TOpener($prev);

                $ul.sortable();
                $ul.disableSelection();
            }
        }
        MenuEditor.updateButtons($main);
    });

    /* PRIVATE METHODS */
    function copyItem($item) {
        var copyingData = $item.data();

        var objectData = {
            "system" : copyingData["system"],
            "owner" : copyingData["owner"],
            "is_owner" : copyingData["is_owner"],
            "locked" : copyingData["locked"],
            "editable" : copyingData["editable"],
            "basekey" : copyingData["basekey"] + "copy",
            "__key" : copyingData["__key"] + "copy",
            "__parent" : copyingData["__parent"],
            "content" : copyingData["content"],
            "default_value" : copyingData["default_value"],
            "enabled" : copyingData["enabled"],
            "only_admins" : copyingData["only_admins"],
            "file_types" : copyingData["file_types"],
            "max" : copyingData["max"],
            "min" : copyingData["min"],
            "multiple" : copyingData["multiple"],
            "option_titles" : copyingData["option_titles"],
            "option_values" : copyingData["option_values"],
            "required" : copyingData["required"],
            "step" : copyingData["step"],
            "title" : copyingData["title"] + " (Copy)",
            "toggle_elements" : copyingData["toggle_elements"],
            "type" : copyingData["type"],
            "url" : copyingData["url"],
            "value" : copyingData["value"],
            "hint" : copyingData["hint"],
            "description" : copyingData["description"],
            "large_screen_size" : copyingData["large_screen_size"],
            "medium_screen_size" : copyingData["medium_screen_size"],
            "small_screen_size" : copyingData["small_screen_size"],
            "max_selection" : copyingData["max_selection"],
            "min_selection" : copyingData["min_selection"],
            "expression" : copyingData["expression"],
            "message" : copyingData["message"],
        }

        var btnGroup = TButtonGroup(objectData.editable);

        var container_class = "editor-title-container disabled";
        if (objectData.enabled) {
            container_class = "editor-title-container";
        }
        var $titleContainer = $("<div>").addClass(container_class);
        
        var $pTitle = $("<p>").addClass("title").append(objectData["title"]);
        var $pKey = $("<p>").addClass("__key").append(objectData["__key"]);

        var $iconLocked = '<i class="fas fa-lock editor-lock-icon"></i>';
        if (1 == objectData.editable) {
            $iconLocked = '';
        }

        var $iconRequired = '<i class="fa fa-asterisk editor-required-icon"></i>';
        if (0 == objectData.required) {
            $iconRequired = '';
        }

        var typeTitle = getTypeTitle(objectData.type);
        var $pType = $("<p>").addClass("type").append(typeTitle);
        $titleContainer.append($pTitle).append($iconRequired).append($iconLocked).append($pKey).append($pType);

        var div = $('<div>').css({"overflow": "auto"}).append("&nbsp;").append($titleContainer).append(btnGroup);

        var $li = $("<li>");
        $li.data(objectData);

        $li.attr("id", objectData["__key"]);

        $li.addClass('list-group-item').append(div);

        if ("" == objectData["__parent"]) {
            $main.append($li);
        } else {
            $parent = $(document.getElementById(objectData["__parent"]));
            $ul = $parent.children('ul');
            if ($ul.length > 0) {
                $ul.append($li);
            } else {
                $ul = $('<ul>').addClass('pl-0').css('padding-top', '10px');
                $ul.append($li);
                $parent.append($ul);
                
                $parent.addClass('sortableListsOpen');
                TOpener($parent);

                $ul.sortable();
                $ul.disableSelection();
            }
        }

        $li = null;
        
        MenuEditor.updateButtons($main);
        resetForm();

        document.getElementById("exception_key").value = "";
        $("#exception_key").trigger("click");

        var children = $('ul:first>li', $item);
        var __key = objectData["__key"];
        if (children.length > 0) {
            copyChildren(children, __key);
        }
    }

    function copyChildren(children, parentKey) {
        var currentLi = null;
        var copyingData = {};
        var objectData = {};
        var btnGroup = null;
        var container_class = "";
        var $titleContainer = null;
        var $pTitle = null;
        var $pKey = null;
        var $iconLocked = null;
        var $iconRequired = null;
        var typeTitle = "";
        var $pType = null;
        var div = null;
        var $newLi = null;
        var $parent = null;
        var $ul = null;
        var subchildren = null
        var __key = "";

        for (let index = 0; index < children.length; index++) {
            currentLi = children[index];
            copyingData = $(currentLi).data();
            objectData = {
                "system" : copyingData["system"],
                "owner" : copyingData["owner"],
                "is_owner" : copyingData["is_owner"],
                "locked" : copyingData["locked"],
                "editable" : copyingData["editable"],
                "basekey" : copyingData["basekey"],
                "__key" : (parentKey + "." + copyingData["basekey"]),
                "__parent" : parentKey,
                "content" : copyingData["content"],
                "default_value" : copyingData["default_value"],
                "enabled" : copyingData["enabled"],
                "only_admins" : copyingData["only_admins"],
                "file_types" : copyingData["file_types"],
                "max" : copyingData["max"],
                "min" : copyingData["min"],
                "multiple" : copyingData["multiple"],
                "option_titles" : copyingData["option_titles"],
                "option_values" : copyingData["option_values"],
                "required" : copyingData["required"],
                "step" : copyingData["step"],
                "title" : copyingData["title"],
                "toggle_elements" : copyingData["toggle_elements"],
                "type" : copyingData["type"],
                "url" : copyingData["url"],
                "value" : copyingData["value"],
                "hint" : copyingData["hint"],
                "description" : copyingData["description"],
                "large_screen_size" : copyingData["large_screen_size"],
                "medium_screen_size" : copyingData["medium_screen_size"],
                "small_screen_size" : copyingData["small_screen_size"],
                "max_selection" : copyingData["max_selection"],
                "min_selection" : copyingData["min_selection"],
                "expression" : copyingData["expression"],
                "message" : copyingData["message"],
            }
    
            btnGroup = TButtonGroup(objectData.editable);
    
            container_class = "editor-title-container disabled";
            if (objectData.enabled) {
                container_class = "editor-title-container";
            }
            $titleContainer = $("<div>").addClass(container_class);
            
            $pTitle = $("<p>").addClass("title").append(objectData["title"]);
            $pKey = $("<p>").addClass("__key").append(objectData["__key"]);
    
            $iconLocked = '<i class="fas fa-lock editor-lock-icon"></i>';
            if (1 == objectData.editable) {
                $iconLocked = '';
            }
    
            $iconRequired = '<i class="fa fa-asterisk editor-required-icon"></i>';
            if (0 == objectData.required) {
                $iconRequired = '';
            }
    
            typeTitle = getTypeTitle(objectData.type);
            $pType = $("<p>").addClass("type").append(typeTitle);
            $titleContainer.append($pTitle).append($iconRequired).append($iconLocked).append($pKey).append($pType);
    
            div = $('<div>').css({"overflow": "auto"}).append("&nbsp;").append($titleContainer).append(btnGroup);
    
            $newLi = $("<li>");
            $newLi.data(objectData);
    
            $newLi.attr("id", objectData["__key"]);
    
            $newLi.addClass('list-group-item').append(div);
            if ("" == objectData["__parent"]) {
                $main.append($newLi);
            } else {
                $parent = $(document.getElementById(objectData["__parent"]));
                
                $ul = $parent.children('ul');
                if ($ul.length > 0) {
                    $ul.append($newLi);
                } else {
                    $ul = $('<ul>').addClass('pl-0').css('padding-top', '10px');
                    $ul.append($newLi);
                    $parent.append($ul);
                    
                    $parent.addClass('sortableListsOpen');
                    TOpener($parent);
    
                    $ul.sortable();
                    $ul.disableSelection();
                }
            }
    
            $newLi = null;
            
            MenuEditor.updateButtons($main);
            resetForm();
    
            document.getElementById("exception_key").value = "";
            $("#exception_key").trigger("click");
    
            subchildren = $('ul:first>li', currentLi);
            __key = objectData["__key"];
            if (subchildren.length > 0) {
                copyChildren(subchildren, __key);
            }
        }
    }

    function editItem($item) {
        var data = $item.data();

        $("#item_data").data("current_data", data);
        
        document.getElementById("currentKey").value = data["__key"];
        document.getElementById("currentParent").value = data["__parent"];

        /* $("#groupTypeWarning").addClass("d-none");
        document.getElementById("type").disabled = false;
        
        if (("group" == data["type"]) || ("selection_group" == data["type"])) {
            console.log("gh2")
            $("#groupTypeWarning").removeClass("d-none");
            document.getElementById("type").disabled = true;
        } */

        /* $.each(data, function (p, v) {
            $form.find("[name=" + p + "]").val(v);
        }); */
        $form.find(".item-menu").first().focus();

        $('#item_data').trigger('click');        
    }

    function resetForm() {
        $form[0].reset();
        itemEditing = null;
    }

    function stringToArray(str) {
        try {
            var obj = JSON.parse(str);
        } catch (err) {
            console.log('The string is not a json valid.');
            return null;
        }
        return obj;
    }

    function TButton(attr) {
        return $("<a>").addClass(attr.classCss).addClass('clickable').attr("href", "#").html(attr.text);
    }

    function TButtonGroup(editable) {
        var $divbtn = $('<div>').addClass('btn-group item-btn-group float-right');
        var $btnCopy = TButton({classCss: 'btn btn-sm btnCopy', text: '<i class="fas fa-copy clickable"></i>'});
        var $btnEdit = TButton({classCss: 'btn btn-sm btnEdit', text: settings.labelEdit});
        var $btnRemv = TButton({classCss: 'btn btn-sm btnRemove', text: settings.labelRemove});
        /* var $btnUp = TButton({classCss: 'btn btn-sm btnUp btnMove', text: '<i class="fas fa-angle-up clickable"></i>'});
        var $btnDown = TButton({classCss: 'btn btn-sm btnDown btnMove', text: '<i class="fas fa-angle-down clickable"></i>'});
        var $btnOut = TButton({classCss: 'btn btn-sm btnOut btnMove', text: '<i class="fas fa-level-down-alt clickable"></i>'});
        var $btnIn = TButton({classCss: 'btn btn-sm btnIn btnMove', text: '<i class="fas fa-level-up-alt clickable"></i>'}); */

        if (1 == editable) {
            $divbtn.append($btnCopy).append($btnEdit).append($btnRemv);
        }

        return $divbtn;
    }

    function getButtonCopyKey(__key) {
        return '<button type="button" class="copyKey" '
            + 'title="' + document.getElementById("copyBtnTitle").innerHTML + '"'
            + 'data-key="' + __key + '"'
            + 'default-text="' + document.getElementById("copyBtnDefaultText").innerHTML + '"'
            + 'copied-text="' + document.getElementById("copyBtnCopiedText").innerHTML + '"'
            + '>'
            + document.getElementById("copyBtnDefaultText").innerHTML
            + '</button>';
    }

    /**
     * @param {array} arrayItem Object Array
     * @param {int} depth Depth sub-menu
     * @return {object} jQuery Object
     **/
    function createTree(arrayItem, depth) {
        var level = (typeof (depth) === 'undefined') ? 0 : depth;
        var $elem = (level === 0) ? $main : $('<ul>').addClass('pl-0').css('padding-top', '10px');

        Object.keys(arrayItem).forEach(__key => {
            v = arrayItem[__key];

            var isParent = (typeof (v.children) !== "undefined") && ($.isArray(v.children));
            var objectData = {
                system: 0,
                owner: 0,
                is_owner: 0,
                locked: false,
                editable: 0,
                basekey: "",
                __key: "",
                __parent: "yyyy",
                content: "",
                default_value: "",
                enabled: true,
                only_admins: false,
                file_types: "",
                max: 0.00,
                min: 0.00,
                multiple: false,
                option_titles: "",
                option_values: "",
                required: false,
                step: 0.00,
                title: "",
                toggle_elements: "",
                type: "",
                url: "",
                value: "",
                hint: "",
                description: "",
                large_screen_size: 12,
                medium_screen_size: 12,
                small_screen_size: 12,
                min_selection: 0,
                max_selection: 0,
                expression: "",
                message: "",
            };

            var temp = $.extend({}, v);
            if (isParent){ 
                delete temp['children'];
            }
            $.extend(objectData, temp);

            var $li = $('<li>').addClass('list-group-item');
            $li.attr("id", v.__key);
            $li.data(objectData);
            var $div = $('<div>').css('overflow', 'auto');

            var container_class = "editor-title-container disabled";
            if (objectData.enabled) {
                container_class = "editor-title-container";
            }
            var $titleContainer = $("<div>").addClass(container_class);

            var $pTitle = $("<p>").addClass("title").append(v.title);
            
            var $pKey = $("<p>").addClass("__key").append(v.__key).append(getButtonCopyKey(v.__key));

            var $iconLocked = '<i class="fas fa-lock editor-lock-icon"></i>';
            if (1 == objectData.editable) {
                $iconLocked = '';
            }

            var $iconRequired = '<i class="fa fa-asterisk editor-required-icon"></i>';
            if (0 == objectData.required) {
                $iconRequired = '';
            }

            var typeTitle = getTypeTitle(v.type);
            var $pType = $("<p>").addClass("type").append(typeTitle);
            $titleContainer.append($pTitle).append($iconRequired).append($iconLocked).append($pKey).append($pType);

            var $divbtn =  TButtonGroup(v.editable);
            $div.append("&nbsp;").append($titleContainer).append($divbtn);
            $li.append($div);
            if (isParent) {
                $li.append(createTree(v.children, level + 1));
            }
            $elem.append($li);
        });

        $elem.sortable();
        $elem.disableSelection();
        return $elem;
    }

    function getTypeTitle(key) {
        var options = {
            "group" : "Group",
            "toggle" : "Toggle",
            "checkbox" : "Checkbox",
            "colorpicker" : "Color Picker",
            "datepicker" : "Date Picker",
            "datetimepicker" : "Date Time Picker",
            "dropdown" : "Dropdown",
            "file" : "File",
            "html_editor" : "HTML Editor",
            "iconpicker" : "Icon Picker",
            "integer" : "Integer",
            "link_button" : "Link (Button)",
            "link_text" : "Link (Text)",
            "number" : "Number",
            "password" : "Password",
            "radio" : "Radio",
            "readonly_content" : "Readonly Content",
            "selection_group" : "Selection Group",
            "selection_item" : "Selection Item",
            "shorttext" : "Shorttext",
            "switch" : "Switch",
            "textarea" : "Textarea",
            "timepicker" : "Time Picker",
        };

        return options[key];
    }

    function TOpener(li){
        var opener = $('<span>').addClass('openerStyle sortableListsOpener ' + options.opener.openerClass).css(options.opener.openerCss)
                .on('mousedown touchstart', function (e){
                    var li = $(this).closest('li');
                    if (li.hasClass('sortableListsClosed')) {
                        li.iconOpen(options);
                    } else {
                        li.iconClose(options);
                    }
                    return false; // Prevent default
                });
        opener.prependTo(li.children('div').first());
        if ( !li.hasClass('sortableListsOpen') ) {
            li.iconClose(options);
        } else {
            li.iconOpen(options);
        }
    }
    function setOpeners() {
        $main.find('li').each(function () {
            var $li = $(this);
            if ($li.children('ul').length) {
                TOpener($li);
            }
        });
    }
    /* PUBLIC METHODS */
    this.setForm = function(form){
        $form = form;
    };

    this.getForm = function(){
        return $form;
    };

    this.setUpdateButton = function($btn){
        $updateButton = $btn;
        /*$updateButton.attr('disabled', true);*/
        itemEditing = null;
    };

    this.getUpdateButton = function(){
        return $updateButton;
    };

    this.getCurrentItem = function(){
        return itemEditing;
    };

    this.update = function(){
        var $cEl = this.getCurrentItem();
        
        if ($cEl===null){
            return;
        }

        var oldParent = document.getElementById("currentParent").value;
        var newParent = document.getElementById("__parent").value;

        var newTitle = document.getElementById("title").value;

        var oldKey = document.getElementById("currentKey").value;
        var baseKey = document.getElementById("basekey").value;
        var newKey = baseKey;//this.convertTitleToConfigName(newTitle);
        if ("" !== newParent) {
            newKey = newParent + "." + newKey;
        }

        if (oldKey != newKey) {
            this.updateChildrenKeys($cEl, oldKey, newKey);
        }

        var objectData = {
            "system" : document.getElementById("system").value,
            "owner" : document.getElementById("owner").value,
            "is_owner" : document.getElementById("is_owner").value,
            "locked" : document.getElementById("locked").checked,
            "editable" : document.getElementById("editable").value,
            "basekey" : baseKey,
            "__key" : newKey,
            "__parent" : newParent,
            "content" : document.getElementById("content").value,
            "default_value" : this.getItemDefaultValue(document.getElementById("type").value),
            "enabled" : document.getElementById("enabled").checked,
            "only_admins" : document.getElementById("only_admins").checked,
            "file_types" : document.getElementById("file_types").value,
            "max" : document.getElementById("max").value,
            "min" : document.getElementById("min").value,
            "multiple" : document.getElementById("multiple").checked,
            "option_titles" : document.getElementById("option_titles").value,
            "option_values" : document.getElementById("option_values").value,
            "required" : document.getElementById("required").checked,
            "step" : document.getElementById("step").value,
            "title" : newTitle,
            "toggle_elements" : $("#toggle_elements").val(),
            "type" : document.getElementById("type").value,
            "url" : document.getElementById("url").value,
            "value" : document.getElementById("value").value,
            "hint" : document.getElementById("hint").value,
            "description" : $("#description").summernote('code'),
            "large_screen_size" : document.getElementById("large_screen_size").value,
            "medium_screen_size" : document.getElementById("medium_screen_size").value,
            "small_screen_size" : document.getElementById("small_screen_size").value,
            "max_selection" : document.getElementById("max_selection").value,
            "min_selection" : document.getElementById("min_selection").value,
            "expression" : document.getElementById("expression").value,
            "message" : document.getElementById("message").value,
        }

        if (oldParent != newParent) {
            this.update_parentChanged($cEl, oldParent, newParent, oldKey, newKey, objectData);
        } else {
            this.update_parentNotChanged($cEl, oldParent, newParent, oldKey, newKey, objectData);
        }

        document.getElementById("exception_key").value = "";
        $("#exception_key").trigger("click");
    };

    this.update_parentChanged = function(currentElement, oldParent, newParent, oldKey, newKey, objectData) {
        var list = currentElement.closest('ul');
        var children = this.getChildrenData(currentElement);

        currentElement.remove();

        var isMainContainer = false;
        if (typeof list.attr('id') !== 'undefined') {
            isMainContainer = (list.attr('id').toString() === idSelector);
        }
        if ((!list.children().length) && (!isMainContainer)) {
            list.prev('div').children('.sortableListsOpener').first().remove();
            list.remove();
        }

        var btnGroup = TButtonGroup(objectData.editable);
        
        var container_class = "editor-title-container disabled";
        if (objectData.enabled) {
            container_class = "editor-title-container";
        }
        var $titleContainer = $("<div>").addClass(container_class);
        
        var $pTitle = $("<p>").addClass("title").append(objectData.title);
        var $pKey = $("<p>").addClass("__key").append(objectData.__key);

        var $iconLocked = '<i class="fas fa-lock editor-lock-icon"></i>';
        if (1 == objectData.editable) {
            $iconLocked = '';
        }

        var $iconRequired = '<i class="fa fa-asterisk editor-required-icon"></i>';
        if (0 == objectData.required) {
            $iconRequired = '';
        }

        var typeTitle = getTypeTitle(objectData.type);
        var $pType = $("<p>").addClass("type").append(typeTitle);
        $titleContainer.append($pTitle).append($iconRequired).append($iconLocked).append($pKey).append($pType);

        var div = $('<div>').css({"overflow": "auto"}).append("&nbsp;").append($titleContainer).append(btnGroup);
        var $li = $("<li>").data(objectData);
        $li.attr("id", newKey);
        $li.addClass('list-group-item').append(div);

        if ("" == newParent) {
            $main.append($li);
        } else {
            var $parent = $(document.getElementById(newParent));
            var $ul = $parent.children('ul');

            if ($ul.length > 0)
                $ul.append($li);
            else {
                var $ul = $('<ul>').addClass('pl-0').css('padding-top', '10px');
                $parent.append($ul);
                $ul.append($li);
                $parent.addClass('sortableListsOpen');
                TOpener($parent);

                $ul.sortable();
                $ul.disableSelection();
            }
        }

        if (children.length > 0) {
            this.updateChildren(children);
        }

        MenuEditor.updateButtons($main);
        resetForm();
    }

    this.update_parentNotChanged = function(currentElement, oldParent, newParent, oldKey, newKey, objectData) {
        var li = currentElement[0];
        var liDataContainer = $(".editor-title-container", li).first()

        liDataContainer.html("");

        var container_class = "editor-title-container disabled";
        if (objectData.enabled) {
            container_class = "editor-title-container";
        }

        liDataContainer.attr('class', container_class);

        var $pTitle = $("<p>").addClass("title").append(objectData.title);
        var $pKey = $("<p>").addClass("__key").append(objectData.__key);

        var $iconLocked = '<i class="fas fa-lock editor-lock-icon"></i>';
        if (1 == objectData.editable) {
            $iconLocked = '';
        }

        var $iconRequired = '<i class="fa fa-asterisk editor-required-icon"></i>';
        if (0 == objectData.required) {
            $iconRequired = '';
        }

        var typeTitle = getTypeTitle(objectData.type);
        var $pType = $("<p>").addClass("type").append(typeTitle);

        liDataContainer.append($pTitle).append($iconRequired).append($iconLocked).append($pKey).append($pType);

        li.id = newKey;

        $(li).data(objectData);

        return;
    }

    this.getChildrenData = function(element) {
        var resultData = [];

        var arrLi = $('ul:first>li', element);

        var tempOpject = {
            "system": 0,
            "owner": 0,
            "is_owner": 0,
            "locked": false,
            "editable": 0,
            "basekey" : "",
            "__key" : "",
            "__parent" : "",
            "content" : "",
            "default_value" : null,
            "enabled" : true,
            "only_admins": false,
            "file_types" : "",
            "max" : 0.00,
            "min" : 0.00,
            "multiple" : false,
            "option_titles" : null,
            "option_values" : null,
            "required" : false,
            "step" : 0.00,  
            "title" : "",
            "toggle_elements" : [],
            "type" : "",
            "url" : "",
            "value" : null,
            "hint" : "",
            "description" : "",
            "large_screen_size": 12,
            "medium_screen_size": 12,
            "small_screen_size": 12,
            "max_selection": 0,
            "min_selection": 0,
            "expression" : "",
            "message" : "",
        };

        var children = [];
        var newObject = {};

        for (let index = 0; index < arrLi.length; index++) {
            const li = arrLi[index];

            newObject = tempOpject;
            newObject = $(li).data();
            
            children = this.getChildrenData(li);

            if (children.length > 0) {
                newObject["children"] = children;
            }

            resultData.push(newObject);
        }

        return resultData;
    };

    this.updateChildrenKeys = function(element, oldParentKey, newParentKey) {
        var arrLi = $('ul:first>li', element);
        for (let index = 0; index < arrLi.length; index++) {
            const li = arrLi[index];

            const liOldKey = $(li).data("__key");
            const liNewKey = liOldKey.replace((oldParentKey + "."), (newParentKey + "."));
            $(li).data("__key", liNewKey);
            $(li).data("__parent", newParentKey);

            $("div>div>p.__key:first", li).html(liNewKey);
            
            this.updateChildrenKeys(li, liOldKey, liNewKey);
        }
    };

    this.updateChildren = function(children) {
        var tempOpject = {
            "system": 0,
            "owner": 0,
            "is_owner": 0,
            "locked": false,
            "editable": 0,
            "basekey" : "",
            "__key" : "",
            "__parent" : "",
            "content" : "",
            "default_value" : null,
            "enabled" : true,
            "only_admins": false,
            "file_types" : "",
            "max" : 0.00,
            "min" : 0.00,
            "multiple" : false,
            "option_titles" : null,
            "option_values" : null,
            "required" : false,
            "step" : 0.00,  
            "title" : "",
            "toggle_elements" : [],
            "type" : "",
            "url" : "",
            "value" : null,
            "hint" : "",
            "description" : "",
            "large_screen_size": 12,
            "medium_screen_size": 12,
            "small_screen_size": 12,
            "max_selection": 0,
            "min_selection": 0,
            "expression" : "",
            "message" : "",
        };

        var objectData = {},
            btnGroup = null,
            div = null,
            $li = null,
            $parent = null,
            $ul = null,
            subchildren = [],
            container_class = null,
            $titleContainer = null,
            $pTitle = null,
            $pKey = null,
            $pType = null;

        children.forEach(element => {
            objectData = tempOpject;
            objectData["system"] = element["system"];
            objectData["owner"] = element["owner"];
            objectData["is_owner"] = element["is_owner"];
            objectData["locked"] = element["locked"];
            objectData["editable"] = element["editable"];
            objectData["basekey"] = element["basekey"];
            objectData["__key"] = element["__key"];
            objectData["__parent"] = element["__parent"];
            objectData["content"] = element["content"];
            objectData["default_value"] = element["default_value"];
            objectData["enabled"] = element["enabled"];
            objectData["only_admins"] = element["only_admins"];
            objectData["file_types"] = element["file_types"];
            objectData["max"] = element["max"];
            objectData["min"] = element["min"];
            objectData["multiple"] = element["multiple"];
            objectData["option_titles"] = element["option_titles"];
            objectData["option_values"] = element["option_values"];
            objectData["required"] = element["required"];
            objectData["step"] = element["step"];
            objectData["title"] = element["title"];
            objectData["toggle_elements"] = element["toggle_elements"];
            objectData["type"] = element["type"];
            objectData["url"] = element["url"];
            objectData["value"] = element["value"];
            objectData["hint"] = element["hint"];
            objectData["description"] = element["description"];
            objectData["large_screen_size"] = element["large_screen_size"];
            objectData["medium_screen_size"] = element["medium_screen_size"];
            objectData["small_screen_size"] = element["small_screen_size"];
            objectData["max_selection"] = element["max_selection"];
            objectData["min_selection"] = element["min_selection"];
            objectData["expression"] = element["expression"];
            objectData["message"] = element["message"];

            
            
            btnGroup = TButtonGroup(objectData["editable"]);

            container_class = "editor-title-container disabled";
            if (objectData.enabled) {
                container_class = "editor-title-container";
            }
            $titleContainer = $("<div>").addClass(container_class);
            
            $pTitle = $("<p>").addClass("title").append(objectData.title);
            $pKey = $("<p>").addClass("__key").append(objectData.__key);

            var $iconLocked = '<i class="fas fa-lock editor-lock-icon"></i>';
            if (1 == objectData.editable) {
                $iconLocked = '';
            }

            var $iconRequired = '<i class="fa fa-asterisk editor-required-icon"></i>';
            if (0 == objectData.required) {
                $iconRequired = '';
            }

            var typeTitle = getTypeTitle(objectData.type);
            $pType = $("<p>").addClass("type").append(typeTitle);
            $titleContainer.append($pTitle).append($iconRequired).append($iconLocked).append($pKey).append($pType);

            div = $('<div>').css({"overflow": "auto"}).append("&nbsp;").append($titleContainer).append(btnGroup);
            $li = $("<li>").data(objectData);
            $li.attr("id", objectData["__key"]);
            $li.addClass('list-group-item').append(div);

            $parent = $(document.getElementById(objectData["__parent"]));
            $ul = $parent.children('ul');
            if ($ul.length > 0)
                $ul.append($li);
            else {
                $ul = $('<ul>').addClass('pl-0').css('padding-top', '10px');
                $parent.append($ul);
                $ul.append($li);
                $parent.addClass('sortableListsOpen');
                TOpener($parent);

                $ul.sortable();
                $ul.disableSelection();
            }
            
            subchildren = (typeof element["children"] === 'undefined') ? [] : element["children"];

            if (subchildren.length > 0) {
                this.updateChildren(subchildren);
            }
        });
    }

    this.getParentKey = function(elementKey) {
        var parent = '';

        if ('' != elementKey) {
           var parts = elementKey.split(".");
           var base = parts[parts.length-1];

           if (parts.length > 1) {
               parent = elementKey.replace(('.' + base), "");
           }
        }

        return parent;
    }
   
    this.add = function(){
        setTimeout(function() {
            const xyz = 4;
        }, 500);
        
        var newParentKey = document.getElementById("__parent").value;
        var newTitle = document.getElementById("title").value;
        var baseKey = document.getElementById("basekey").value;
        var newKey = baseKey;
        if ("" !== newParentKey) {
            newKey = newParentKey + "." + newKey;
        }

        var objectData = {
            "system": document.getElementById("system").value,
            "owner": 0,
            "is_owner": 1,
            "locked": document.getElementById("locked").checked,
            "editable": 1,
            "basekey": baseKey,
            "__key" : newKey,
            "__parent" : newParentKey,
            "content" : document.getElementById("content").value,
            "default_value" : this.getItemDefaultValue(document.getElementById("type").value),
            "enabled" : document.getElementById("enabled").checked,
            "only_admins" : document.getElementById("only_admins").checked,
            "file_types" : document.getElementById("file_types").value,
            "max" : document.getElementById("max").value,
            "min" : document.getElementById("min").value,
            "multiple" : document.getElementById("multiple").checked,
            "option_titles" : document.getElementById("option_titles").value,
            "option_values" : document.getElementById("option_values").value,
            "required" : document.getElementById("required").checked,
            "step" : document.getElementById("step").value,
            "title" : newTitle,
            "toggle_elements" : $("#toggle_elements").val(),
            "type" : document.getElementById("type").value,
            "url" : document.getElementById("url").value,
            "value" : document.getElementById("value").value,
            "hint" : document.getElementById("hint").value,
            "description" : $("#description").summernote('code'),
            "large_screen_size" : document.getElementById("large_screen_size").value,
            "medium_screen_size" : document.getElementById("medium_screen_size").value,
            "small_screen_size" : document.getElementById("small_screen_size").value,
            "max_selection" : document.getElementById("max_selection").value,
            "min_selection" : document.getElementById("min_selection").value,
            "expression" : document.getElementById("expression").value,
            "message" : document.getElementById("message").value,
        };

        var btnGroup = TButtonGroup(objectData.editable);

        var container_class = "editor-title-container disabled";
        if (objectData.enabled) {
            container_class = "editor-title-container";
        }
        var $titleContainer = $("<div>").addClass(container_class);
        
        var $pTitle = $("<p>").addClass("title").append(newTitle);
        var $pKey = $("<p>").addClass("__key").append(newKey);

        var $iconLocked = '<i class="fas fa-lock editor-lock-icon"></i>';
        if (1 == objectData.editable) {
            $iconLocked = '';
        }

        var $iconRequired = '<i class="fa fa-asterisk editor-required-icon"></i>';
        if (0 == objectData.required) {
            $iconRequired = '';
        }

        var typeTitle = getTypeTitle(objectData.type);
        var $pType = $("<p>").addClass("type").append(typeTitle);
        $titleContainer.append($pTitle).append($iconRequired).append($iconLocked).append($pKey).append($pType);

        var div = $('<div>').css({"overflow": "auto"}).append("&nbsp;").append($titleContainer).append(btnGroup);

        var $li = $("<li>");
        $li.data(objectData);

        $li.attr("id", newKey);

        $li.addClass('list-group-item').append(div);

        if ("" == newParentKey) {
            $main.append($li);
        } else {
            $parent = $(document.getElementById(newParentKey));
            $ul = $parent.children('ul');
            if ($ul.length > 0) {
                $ul.append($li);
            } else {
                $ul = $('<ul>').addClass('pl-0').css('padding-top', '10px');
                $ul.append($li);
                $parent.append($ul);
                
                $parent.addClass('sortableListsOpen');
                TOpener($parent);

                $ul.sortable();
                $ul.disableSelection();
            }
        }

        $li = null;
        
        MenuEditor.updateButtons($main);
        resetForm();

        document.getElementById("exception_key").value = "";
        $("#exception_key").trigger("click");
    };

    this.getItemDefaultValue = function(type) {
        var default_val = "";

        if (
            ("checkbox" == type)
            || ("switch" == type)
            || ("toggle" == type)
            ) {
            default_val = document.getElementById("default_value_checkbox").value;
        } else if (
            ("dropdown" == type)
            || ("password" == type)
            || ("radio" == type)
            || ("shorttext" == type)
            ) {
            default_val = document.getElementById("default_value_text").value;
        } else if (("integer" == type) || ("number" == type)){
            default_val = document.getElementById("default_value_number").value;
        } else if ("datepicker" == type){
            default_val = document.getElementById("default_value_datepicker").value;
        } else if ("datetimepicker" == type){
            default_val = document.getElementById("default_value_datetimepicker").value;
        } else if ("timepicker" == type){
            default_val = document.getElementById("default_value_timepicker").value;
        } else if ("html_editor" == type) {
            default_val = $("#default_value_html_editor").summernote('code');
        } else if ("textarea" == type) {
            default_val = $(document.getElementById("default_value_textarea")).val();
        } else if ("colorpicker" == type) {
            default_val = document.getElementById("default_value_colorpicker").value;
        } else if ("iconpicker" == type) {
            default_val = document.getElementById("default_value_iconpicker").value
        } else if ("selection_group" == type) {
            default_val = $(document.getElementById("default_value_multiple")).val().join(",");
        }

        return default_val;
    }
    /**
     * Data Output
     * @return String JSON menu scheme
     */
    this.getString = function () {
        var obj = $main.sortableListsToJson();
        return JSON.stringify(obj);
    };
    /**
     * Data Input
     * @param {Array} Object array. The nested menu scheme
     */
    this.setData = function (arrayItem) {
        /* var start = Date.now();
        console.log("start" + start) */
        
        if (arrayItem !== null) {
            $main.empty();
            var menu = createTree(arrayItem);
            if (!sortableReady) {
                menu.sortableLists(settings.listOptions);
                sortableReady = true;
            } else {
                setOpeners();
            }
            MenuEditor.updateButtons($main);
        }

        /* var end = Date.now();
        console.log("end" + end)
        console.log("listenin yüklenme süresi:" + (end-start) + " ms.") */
    };

    this.convertTitleToConfigName = function(strName) {
        var strReturnValue = strName;

        strReturnValue = strReturnValue.replace(/Ç/g, 'c');
        strReturnValue = strReturnValue.replace(/ç/g, 'c');
        strReturnValue = strReturnValue.replace(/Ý/g, 'i');
        strReturnValue = strReturnValue.replace(/ý/g, 'i');
        strReturnValue = strReturnValue.replace(/I/g, 'i');
        strReturnValue = strReturnValue.replace(/İ/g, 'i');
        strReturnValue = strReturnValue.replace(/ı/g, 'i');
        strReturnValue = strReturnValue.replace(/Ð/g, 'g');
        strReturnValue = strReturnValue.replace(/ð/g, 'g');
        strReturnValue = strReturnValue.replace(/Ğ/g, 'g');
        strReturnValue = strReturnValue.replace(/ğ/g, 'g');
        strReturnValue = strReturnValue.replace(/Ö/g, 'o');
        strReturnValue = strReturnValue.replace(/ö/g, 'o');
        strReturnValue = strReturnValue.replace(/Þ/g, 's');
        strReturnValue = strReturnValue.replace(/þ/g, 's');
        strReturnValue = strReturnValue.replace(/ş/g, 's');
        strReturnValue = strReturnValue.replace(/Ş/g, 's');
        strReturnValue = strReturnValue.replace(/Ü/g, 'u');
        strReturnValue = strReturnValue.replace(/ü/g, 'u');
        strReturnValue = strReturnValue.replace(/"/g, '');
        strReturnValue = strReturnValue.replace(/\'/g, '');
        strReturnValue = strReturnValue.replace(/\?/g, '');
        strReturnValue = strReturnValue.replace(/:/g, '');
        strReturnValue = strReturnValue.replace(/\//g, '');
        strReturnValue = strReturnValue.replace(/!/g, '');
        strReturnValue = strReturnValue.replace(/,/g, '');
        strReturnValue = strReturnValue.replace(/\(/g, '');
        strReturnValue = strReturnValue.replace(/\)/g, '');
        strReturnValue = strReturnValue.replace(/-/g, '');
        strReturnValue = strReturnValue.replace(/\./g, '');
        strReturnValue = strReturnValue.replace(/\+/g, '');
        strReturnValue = strReturnValue.replace(/\*/g, '');
        strReturnValue = strReturnValue.replace(/#/g, '');
        strReturnValue = strReturnValue.replace(/ /g, '');
        strReturnValue = strReturnValue.replace(/__/g, '');
        
        return strReturnValue.toLowerCase();
    }
};
/* STATIC METHOD */
/**
 * Update the buttons on the list. Only the buttons 'Up', 'Down', 'In', 'Out'
 * @param {jQuery} $mainList The unorder list 
 **/
MenuEditor.updateButtons = function($mainList){
    $mainList.find('.btnMove').show();
    $mainList.updateButtons();
};