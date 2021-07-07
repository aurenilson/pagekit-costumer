import { ValidationObserver, VInput } from '@system/app/components/validation.vue';
import UserSettings from '../../components/user-settings.vue';

window.User = {

    name: 'user-edit',

    el: '#user-edit',

    mixins: [Theme.Mixins.Helper],

    data() {
        return _.extend({ sections: [], processing: false }, window.$data);
    },

    provide: { $components: { VInput } },

    theme: {
        hideEls: ['#user-edit > div:first-child'],
        elements() {
            const vm = this;
            return {
                title: {
                    scope: 'breadcrumbs',
                    type: 'caption',
                    caption: () => {
                        const { trans } = this.$options.filters;
                        return vm.user.id && trans ? trans('Edit User') : trans('Add User');
                    }
                },
                saveuser: {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Save',
                    class: 'uk-button tm-button-success',
                    spinner: () => vm.processing,
                    on: { click: () => vm.submit() },
                    priority: 1
                },
                close: {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: () => vm.user.id ? 'Close' : 'Cancel',
                    class: 'uk-button uk-button-text',
                    attrs: { href: () => vm.$url.route('admin/user') },
                    disabled: () => vm.processing,
                    priority: 0
                }
            };
        }
    },

    created() {
        const sections = [];

        _.forIn(this.$options.components, (component, name) => {
            if (component.section) {
                sections.push(_.extend({ name, priority: 0 }, component.section));
            }
        });

        this.sections = _.sortBy(sections, 'priority');
    },

    mounted() {
        this.tab = UIkit.tab(this.$refs.tab, { connect: this.$theme.getDomElement(this.$refs.content) });
    },

    methods: {

        async submit() {
            const isValid = await this.$refs.observer.validate();
            if (isValid) {
                this.processing = true;
                this.save();
            }
        },

        save() {
            const data = { user: this.user };
            const vm = this;

            this.$trigger('user-save', data);
            this.$resource('api/user{/id}').save({ id: this.user.id }, data).then(function (res) {
                if (!this.user.id) {
                    window.history.replaceState({}, '', this.$url.route('admin/user/edit', { id: res.data.user.id }));
                }
                this.$set(this, 'user', res.data.user);
                this.$notify('User saved.');
            }, function (res) {
                this.$notify(res.data, 'danger');
            }).then(() => {
                setTimeout(() => {
                    vm.processing = false;
                }, 500);
            });
        }

    },

    components: {

        ValidationObserver,
        settings: UserSettings

    }

};

Vue.ready(window.User);
