<script>
export default {
    props: ['element', 'catalog'],

    render(createElement) {
        return createElement(
            this.element.widget.component, 
            {
                props: {
                    "instance_id": this.element.instance_id,
                    "data": this.element.data,
                    "container_guid": this.element.container_guid,
                    "catalog": this.catalog
                }
            }
        );
    }
};
</script>