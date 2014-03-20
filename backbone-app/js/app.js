
/* global window, document, Backbone, _, $, Handlebars */

;(function (window, document, undefined) {

    'use strict';

    window.backboneApp = (function () {

        var API_ROOT, AppRouter, models, views, app;

        API_ROOT = '';

        AppRouter = Backbone.Router.extend({

            routes : {
                "*resourcePath" : "resource"
            },

            resource : function resource(resourcePath) {

                var apiModel;

                resourcePath = (resourcePath) ? resourcePath.replace(/\/+$/, '') : '';
                apiModel = new models.Api({}, { currentPath : resourcePath });
            }
        });

        models = {

            Api : Backbone.Model.extend({

                urlRoot : function urlRoot() {

                    return API_ROOT + '/' + this.currentPath + '/?format=json';
                },

                initialize : function initialize(attributes, options) {

                    var apiView, callback;

                    callback = function callback(model) {
                        apiView = new views.Api({ model : model });
                    };

                    this.currentPath = options.currentPath;
                    this.fetch({ success : callback, error : callback });
                },

                parse : function parse(response) {

                    return {
                        resources : new Backbone.Collection(this.updateResponseArrayWithHash(response.resources)),
                        items : new Backbone.Collection(this.updateResponseArrayWithHash(response.items))
                    };
                },

                updateResponseArrayWithHash : function updateResponseArrayWithHash(array) {

                    var addHashToResponseObject = function addHashToResponseObject(object) {

                        var currentHash = '',
                            currentSlug = this.currentPath.replace(/^.*\/(.+?)$/, '$1'),
                            objectSlug  = object.slug;

                        if (this.currentPath === '') {
                            currentHash = objectSlug;
                        } else if (currentSlug !== objectSlug) {
                            currentHash = this.currentPath + '/' + objectSlug;
                        }

                        return _.extend(object, { hash : currentHash });
                    };

                    return (array) ? array.map(addHashToResponseObject, this) : [];
                }
            })
        };

        views = {

            Api : Backbone.View.extend({

                el : '.content',

                initialize : function initialize() {

                    this.render();
                },

                render : function render() {

                    var resourcesListView, itemsListView, templateContext, template;

                    resourcesListView = this.prepareCollectionView(this.model.get('resources'), 'ResourcesList');
                    itemsListView = this.prepareCollectionView(this.model.get('items'), 'ItemsList');

                    templateContext = {
                        resources : (resourcesListView) ? true : false,
                        items : (itemsListView) ? true : false
                    };

                    if (!templateContext.resources && !templateContext.items) {
                        template = Handlebars.compile($("#api-empty-contents").html());
                        this.$el.html(template());
                    } else {
                        template = Handlebars.compile($("#api-contents").html());
                        this.$el.html(template(templateContext));
                        this
                            .renderCollectionView('.resources-content', resourcesListView)
                            .renderCollectionView('.items-content', itemsListView);
                    }

                    return this;
                },

                prepareCollectionView : function prepareCollectionView(collection, view) {

                    if (collection && collection.length > 0) {
                        return new views[view]({ collection : collection });
                    }
                    return;
                },

                renderCollectionView : function renderCollectionView(selector, view) {

                    if (view) {
                        this.assign(selector, view);
                    }
                    return this;
                }
            }),

            ResourcesList : Backbone.View.extend({

                template : Handlebars.compile($("#resources-list").html()),

                render : function render() {

                    this.$el.html(this.template());
                    this.collection.each(function (resource) {
                        this.assign('.resources-list', new views.ResourcesListItem({ model : resource }));
                    }, this);

                    return this;
                }
            }),

            ResourcesListItem : Backbone.View.extend({

                template : Handlebars.compile($("#resources-list-item").html()),

                render : function render() {

                    this.$el.append(this.template({ resource : this.model.toJSON() }));
                    return this;
                }
            }),

            ItemsList : Backbone.View.extend({

                template : Handlebars.compile($("#items-list").html()),

                render : function render() {

                    this.$el.html(this.template());
                    this.collection.each(function (item) {
                        this.assign('.items-list', new views.ItemsListItem({ model : item }));
                    }, this);

                    return this;
                }
            }),

            ItemsListItem : Backbone.View.extend({

                template : Handlebars.compile($("#items-list-item").html()),

                render : function render() {

                    this.$el.append(this.template({ item : this.model.toJSON() }));
                    return this;
                }
            })
        };

        app = {

            setup : function setup(params) {

                API_ROOT = params.API_ROOT;

                Backbone.View.prototype.assign = function assign(selector, view) {

                    var selectors;

                    if (_.isObject(selector)) {
                        selectors = selector;
                    } else {
                        selectors = {};
                        selectors[selector] = view;
                    }

                    if (!selectors) {
                        return this;
                    }

                    _.each(selectors, function (view, selector) {
                        view.setElement(this.$(selector)).render();
                    }, this);

                    return this;
                };

                Handlebars.registerHelper('join', function(array, delimiter, start, end) {

                    array = [].concat(array);
                    delimiter = (typeof delimiter === "string") ? delimiter : ' ';
                    start = start || 0;
                    end   = end   || array.length;

                    return array.slice(start, end).join(delimiter);
                });
            },

            init : function init(params) {

                var router;

                this.setup(params);

                router = new AppRouter();
                Backbone.history.start();
            }
        };

        return app;

    }());

})(window, document);
