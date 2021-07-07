<?php $view->script('site-index', 'system/site:app/bundle/index.js', ['vue']) ?>

<form id="site" v-cloak>

    <div class="pk-grid-large" uk-grid>
        <div class="pk-width-sidebar">

            <div class="uk-panel">
                <ul class="uk-nav uk-nav-default pk-nav-large">
                    <li class="uk-visible-toggle" :class="{'uk-active': isActive(menu), 'uk-nav-divider': menu.divider}" v-for="menu in divided(menus)">
                        <a class="uk-position-relative" @click.prevent="selectMenu(menu, false)" v-if="!menu.divider">{{ menu.label }}
                            <ul class="uk-invisible-hover uk-iconnav uk-position-center-right uk-margin-small-right" v-if="!menu.fixed && !menu.divider">
                                <li><span class="uk-icon-link" uk-icon="file-edit" :uk-tooltip="'Edit' | trans" delay="500" @click.prevent="editMenu($event, menu)"></span></li>
                                <li><span class="uk-icon-link" uk-icon="trash" :uk-tooltip="'Delete' | trans" delay="500" @click.prevent="removeMenu($event, menu)" v-confirm="'Delete menu?'"></span></li>
                            </ul>
                        </a>
                    </li>
                </ul>
                <p>
                    <a class="uk-button uk-button-default" @click.prevent="editMenu">{{ 'Add Menu' | trans }}</a>
                </p>
            </div>

        </div>
        <div class="pk-width-content">

            <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap uk-grid-small" uk-grid>
                <div class="uk-flex uk-flex-middle uk-flex-wrap" >

                    <h2 class="uk-h3 uk-margin-remove">{{ menu.label }}</h2>

                    <div class="uk-margin-left" v-show="selected.length">
                        <ul class="uk-iconnav">
                            <li><a uk-icon="check" :uk-tooltip="'Publish' | trans" delay="500" @click="status(1)"></a></li>
                            <li><a uk-icon="ban" :uk-tooltip="'Unpublish' | trans" delay="500" @click="status(0)"></a></li>
                            <li v-show="showMove">
                                <a uk-icon="move" :uk-tooltip="'Move' | trans" delay="500" @click.prevent></a>
                                <div uk-dropdown="mode: click">
                                    <ul class="uk-nav uk-dropdown-nav">
                                        <li :class="[m.id == menu.id ? 'uk-disabled uk-text-lighter uk-text-muted': '', 'uk-dropdown-close']" v-for="m in trash(menus)"><a @click="moveNodes(m.id)">{{ m.label }}</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li v-show="showDelete"><a uk-icon="trash" :uk-tooltip="'Delete' | trans" delay="500" @click="removeNodes" v-confirm="'Delete item?'"></a></li>
                        </ul>
                    </div>

                </div>
                <div class="uk-position-relative">

                        <a class="uk-button uk-button-primary" @click.prevent v-show="menu.id != 'trash'">{{ 'Add' | trans }}</a>
                        <div uk-dropdown="mode: click">
                            <ul class="uk-nav uk-dropdown-nav">
                                <li v-for="type in orderBy(protected(types), 'label')">
                                    <a :href="$url.route('admin/site/page/edit', { id: type.id, menu: menu.id })">{{ type.label | trans }}</a>
                                </li>
                            </ul>
                        </div>

                </div>
            </div>

            <div class="uk-overflow-auto">
                <div class="pk-table-fake pk-table-fake-header pk-table-fake-border" ref="table-header" style="opacity: 0;">
                    <div v-if="isMobile" class="pk-table-width-minimum pk-table-collapse uk-flex uk-flex-middle">
                        <span class="uk-icon-button" uk-icon="move" style="background: transparent;"></span>
                    </div>
                    <div :class="['pk-table-width-minimum']">
                        <input class="uk-checkbox" type="checkbox" v-check-all:selected="{ selector: 'input[name=id]' }" number>
                    </div>
                    <div class="pk-table-min-width-150">{{ 'Title' | trans }}</div>
                    <div class="pk-table-width-100 uk-text-center">{{ 'Status' | trans }}</div>
                    <div class="pk-table-width-100">{{ 'Type' | trans }}</div>
                    <div class="pk-table-width-150">{{ 'URL' | trans }}</div>
                </div>

                <vue-nestable v-model="treedata" @change="change" class="pk-table-fake" class-prop="class" ref="nestable" v-show="treedata.length">
                    <vue-nestable-handle :class="{'uk-active': isSelected(item)}" :data-id="item.id" slot-scope="{ item }" :item="item" v-if="!isMobile">
                        <div class="pk-table-width-minimum"><input class="uk-checkbox" type="checkbox" name="id" :value="item.id"></div>
                        <div class="pk-table-min-width-150">
                            <a :href="$url.route('admin/site/page/edit', { id: item.id })">{{ item.title }}</a>
                            <span class="uk-text-muted uk-text-small uk-margin-small-left" v-if="item.data.menu_hide">{{ 'Hidden' | trans }}</span>
                        </div>
                        <div class="uk-position-absolute uk-padding-remove uk-height-1-1">
                            <div class="uk-flex uk-flex-middle uk-flex-center uk-height-1-1">
                                <a class="uk-icon-link uk-invisible-hover" uk-icon="home" :uk-tooltip="'Set as frontpage' | trans" delay="500" v-if="!isFrontpage(item) && item.status && type(item).frontpage !== false" @click="setFrontpage(item)"></a>
                                <i class="uk-text-muted uk-float-right" uk-icon="home-active" :uk-tooltip="'Frontpage' | trans" delay="500" v-if="isFrontpage(item)"></i>
                            </div>
                        </div>
                        <div class="pk-table-width-100 uk-text-center">
                            <a :class="{'pk-icon-circle-danger': !item.status, 'pk-icon-circle-success': item.status}" @click="toggleStatus(item)"></a>
                        </div>
                        <div class="pk-table-width-100">{{ type(item).label }}</div>
                        <div class="pk-table-width-150 pk-table-max-width-150 uk-text-truncate">
                            <a :title="item.url" target="_blank" :href="$url.route(item.url.substr(1))" v-if="item.accessible && item.url">{{ decodeURI(item.url) }}</a>
                            <span v-else>{{ item.path }}</span>
                        </div>
                    </vue-nestable-handle>

                    <div :class="{'uk-active': isSelected(item)}" :data-id="item.id" slot-scope="{ item }" :item="item" v-else>

                        <vue-nestable-handle :item="item" class="pk-table-width-minimum pk-table-collapse">
                            <span class="uk-icon uk-icon-button" style="background: transparent;">
                                <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="more-vertical"><circle cx="10" cy="3" r="2"></circle><circle cx="10" cy="10" r="2"></circle><circle cx="10" cy="17" r="2"></circle></svg>
                            </span>
                        </vue-nestable-handle>
                        <div class="pk-table-width-minimum"><input class="uk-checkbox" type="checkbox" name="id" :value="item.id"></div>
                        <div class="pk-table-min-width-150">
                            <a :href="$url.route('admin/site/page/edit', { id: item.id })">{{ item.title }}</a>
                            <span class="uk-text-muted uk-text-small uk-margin-small-left" v-if="item.data.menu_hide">{{ 'Hidden' | trans }}</span>
                        </div>
                        <div class="uk-position-absolute uk-padding-remove uk-height-1-1">
                            <div class="uk-flex uk-flex-middle uk-flex-center uk-height-1-1">
                                <a class="uk-icon-link uk-invisible-hover" uk-icon="home" :uk-tooltip="'Set as frontpage' | trans" delay="500" v-if="!isFrontpage(item) && item.status && type(item).frontpage !== false" @click="setFrontpage(item)"></a>
                                <i class="uk-text-muted uk-float-right" uk-icon="home-active" :uk-tooltip="'Frontpage' | trans" delay="500" v-if="isFrontpage(item)"></i>
                            </div>
                        </div>
                        <div class="pk-table-width-100 uk-text-center">
                            <a :class="{'pk-icon-circle-danger': !item.status, 'pk-icon-circle-success': item.status}" @click="toggleStatus(item)"></a>
                        </div>
                        <div class="pk-table-width-100">{{ type(item).label }}</div>
                        <div class="pk-table-width-150 pk-table-max-width-150 uk-text-truncate">
                            <a :title="item.url" target="_blank" :href="$url.route(item.url.substr(1))" v-if="item.accessible && item.url">{{ decodeURI(item.url) }}</a>
                            <span v-else>{{ item.path }}</span>
                        </div>
                    </div>

                </vue-nestable>
            </div>

            <h3 class="uk-h2 uk-text-muted uk-text-center" v-show="!treedata.length">{{ 'No pages found.' | trans }}</h3>

        </div>
    </div>

    <v-modal ref="modal" :options="{bgClose: false}">
        <validation-observer v-slot="{ invalid, passes }" slim>
            <div class="uk-form-stacked">

                <div class="uk-modal-header">
                    <h2 class="uk-h4">{{ 'Add Menu' | trans }}</h2>
                </div>

                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <label for="form-name" class="uk-form-label">{{ 'Name' | trans }}</label>
                        <v-input id="form-name" name="label" type="text" view="class: uk-input" rules="required" v-model.trim="edit.label" message="Invalid name." />
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label">{{ 'Menu Positions' | trans }}</label>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-margin-small" v-for="m in config.menus">
                                <label><input class="uk-checkbox" type="checkbox" :value="m.name" v-model="edit.positions"> {{ m.label }}</label>
                                <span class="uk-text-muted" v-if="getMenu(m.name) && getMenu(m.name).id != edit.id">{{ menuLabel(edit.id) }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-text uk-margin-right" type="button" @click.prevent="cancel" autofocus>{{ 'Cancel' | trans }}</button>
                    <button class="uk-button uk-button-primary" :disabled="invalid || !edit.label" @click.prevent="passes(()=>saveMenu(edit))">{{ 'Save' | trans }}</button>
                </div>

            </div>
        </validation-observer>
    </v-modal>

</form>
