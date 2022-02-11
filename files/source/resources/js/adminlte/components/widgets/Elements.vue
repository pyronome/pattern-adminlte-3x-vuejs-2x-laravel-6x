<script>
export default {
    props: ['element'],

    render(createElement) {
        return createElement(
            this.element.widget.component, 
            {
                props: {
                    "instance_id": this.element.instance_id,
                    "enabled": this.element.db_data.enabled,
                    "content": this.element.content,
                }
            }
        );
    }
};
</script>