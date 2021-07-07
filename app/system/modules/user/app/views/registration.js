import { ValidationObserver, VInput } from '@system/app/components/validation.vue';

const Registration = {

    name: 'registration',

    el: '#user-registration',

    data() {
        return {
            user: {},
            error: null,
            hidePassword: true,
            view: {
                type: 'icon',
                containerClass: 'uk-margin',
                class: 'uk-input uk-form-width-large',
                icon: () => (this.hidePassword ? 'lock' : 'unlock'),
                iconClick: () => { this.hidePassword = !this.hidePassword; },
                iconTag: 'a',
                iconDir: 'right'
            }
        };
    },

    methods: {

        async valid() {
            const isValid = await this.$refs.observer.validate();
            if (isValid) {
                this.submit();
            }
        },

        submit() {
            this.error = null;

            this.$http.post('user/registration/register', { user: this.user }).then((res) => {
                window.location.replace(res.data.redirect);
            }, function (error) {
                this.error = error.data;
            });
        }

    },

    components: {
        ValidationObserver,
        VInput
    }

};

export default Registration;

Vue.ready(Registration);
