<script>
    export default {
        props: ["attributes"],
        data() {
            return {
                editing: false,
                body: this.attributes.body
            };
        },

        methods: {
            update() {
                axios.patch("/replies/" + this.attributes.id, {
                        body: this.body
                    })
                    .then(() => {
                        this.editing = false;
                        flash("Updated!");
                    });
            },

            destroy() {
                axios.delete("/replies/" + this.attributes.id)
                    .then(() => {
                        $(this.$el).fadeOut(300, () => {
                            flash("You reply has been deleted!");
                        });
                    });
            }
        }
    };
</script>