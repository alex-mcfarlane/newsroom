/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";

	Object.defineProperty(exports, "__esModule", {
	    value: true
	});

	var _vuedraggable = __webpack_require__(1);

	var _vuedraggable2 = _interopRequireDefault(_vuedraggable);

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

	exports.default = {
	    components: {
	        draggable: _vuedraggable2.default
	    }
	};


	var auth = {
	    data: {
	        user: {
	            "username": "",
	            "email": ""
	        },
	        form_errors: [],
	        errors: []
	    },
	    methods: {
	        login: function login() {
	            //clear error messages
	            this.clearErrors();

	            this.$http.post('api/auth', this.user).then(function (response) {
	                this.saveToken(response.body.token);

	                var index = window.location.href.lastIndexOf('/login');
	                var homeUrl = window.location.href.substring(0, index);
	                window.location.href = homeUrl;
	            }, function (error) {
	                var errors = error.body.errors;

	                if (error.status == 400) {
	                    this.setFormErrors(errors);
	                } else if (error.status == 401) {
	                    this.setErrors(errors);
	                }
	            });
	        },
	        isLoggedIn: function isLoggedIn() {
	            var token = this.getToken();

	            if (token) {
	                var payload = JSON.parse(window.atob(token.split('.')[1]));

	                if (payload.exp > Date.now() / 1000) {
	                    return true;
	                } else {
	                    return false;
	                }
	            } else {
	                return false;
	            }
	        },
	        logout: function logout() {
	            localStorage.removeItem('newsroom-token');
	            window.location.href = window.location.href;
	        },
	        saveToken: function saveToken(token) {
	            localStorage.setItem('newsroom-token', token);
	        },
	        getToken: function getToken() {
	            return localStorage.getItem('newsroom-token');
	        },
	        setErrors: function setErrors(errors) {
	            this.errors = errors;
	        },
	        setFormErrors: function setFormErrors(errors) {
	            this.form_errors = errors;
	        },
	        clearErrors: function clearErrors() {
	            this.form_errors = [];
	            this.errors = [];
	        }
	    }
	};

	new Vue({
	    el: "#vue-app",
	    mixins: [auth],
	    data: {
	        articles: [],
	        featured_articles: [],
	        lookup: [],
	        categories: [],
	        article: {},
	        category: {},
	        headline_article: {
	            image: {
	                path: ''
	            }
	        },
	        headline_article_id: 1,
	        new_feature_article_id: 1,
	        add_headliner: false,
	        edit_headliner: false,
	        fileFormData: new FormData()
	    },
	    components: {
	        draggable: _vuedraggable2.default
	    },
	    created: function created() {
	        this.getArticles();
	        this.getFeaturedArticles();
	        this.getCategories();
	        this.getHeadlineArticle();
	    },
	    methods: {
	        getArticles: function getArticles() {
	            this.$http.get('api/articles').then(function (response) {
	                this.articles = response.body;

	                // Create a lookup dictionary of featured articles
	                // This will allow for easier searching in the future
	                for (var i = 0; i < this.articles.length; i++) {
	                    var article = this.articles[i];

	                    this.lookup[article.id] = article;
	                }
	            }, function (error) {
	                console.log(error);
	            });
	        },
	        getFeaturedArticles: function getFeaturedArticles() {
	            this.$http.get('api/articles/featured').then(function (response) {
	                this.featured_articles = response.body;
	            }, function (error) {
	                console.log(error);
	            });
	        },
	        getUnfeaturedArticles: function getUnfeaturedArticles() {
	            var unFeatured = [];
	            var self = this;

	            var unfeatured = this.articles;

	            this.featured_articles.filter(function (article) {
	                var index = unfeatured.indexOf(self.lookup[article.id]);
	                if (index >= 0) {
	                    unfeatured.splice(index, 1);
	                }
	            });

	            if (unfeatured[0]) {
	                //this.new_feature_article_id = unfeatured[0].id;
	            }

	            return unfeatured;
	        },
	        getCategories: function getCategories() {
	            this.$http.get('api/categories').then(function (response) {
	                this.categories = response.body;

	                this.article.category_id = this.categories[0].id;
	            }, function (error) {
	                console.log(error);
	            });
	        },
	        getHeadlineArticle: function getHeadlineArticle() {
	            this.$http.get('api/articles?headliner=1').then(function (response) {
	                this.headline_article = response.body[0];
	                this.headline_article.body = this.headline_article.body.substring(0, 150) + " ...";

	                this.headline_article_id = this.headline_article.id;
	            }, function (error) {
	                console.log(error);
	            });
	        },
	        changeHeadlineArticle: function changeHeadlineArticle(id) {
	            this.$http.put('api/articles/' + id + '/headline', {}, {
	                headers: {
	                    "Authorization": "Bearer " + this.getToken()
	                }
	            }).then(function (response) {
	                this.headline_article = response.body;
	                this.headline_article.body = this.headline_article.body.substring(0, 150) + " ...";
	            }, function (error) {
	                console.log(error);
	            });
	        },
	        createArticle: function createArticle() {
	            var token = this.getToken();

	            this.$http.post('api/articles', this.article, {
	                headers: {
	                    "Authorization": "Bearer " + token
	                }
	            }).then(function (response) {
	                var article = response.body;

	                //upload image for article
	                this.$http.post('api/articles/' + article.id + '/images', this.fileFormData, {
	                    headers: {
	                        "Authorization": "Bearer " + token
	                    }
	                }).then(function (response) {
	                    article.image = response.body;

	                    //add new article to list of articles
	                    this.articles.push(article);
	                    //close modal and clear entry
	                    $('#add-article').modal('toggle');
	                    this.article = {};
	                }, function (error) {
	                    console.log(error);
	                });
	            }, function (error) {
	                console.log(error);
	            });
	        },
	        createHeadlineArticle: function createHeadlineArticle() {
	            var token = this.getToken();
	            this.article.headliner = true;

	            this.$http.post('api/articles', this.article, {
	                headers: {
	                    "Authorization": "Bearer " + token
	                }
	            }).then(function (response) {
	                this.headline_article = response.body;
	                this.headline_article.body = this.headline_article.body.substring(0, 150) + " ...";

	                //upload image for article
	                this.$http.post('api/articles/' + this.headline_article.id + '/images', this.fileFormData, {
	                    headers: {
	                        "Authorization": "Bearer " + token
	                    }
	                }).then(function (response) {
	                    this.headline_article.image = response.body;

	                    //close modal and clear entry
	                    $('#add_headliner').modal('toggle');
	                    this.article = {};
	                }, function (error) {
	                    console.log(error);
	                });
	            }, function (error) {
	                console.log(error);
	            });
	        },
	        featureArticle: function featureArticle(id) {
	            var orderId = this.featured_articles.length + 1;
	            this.$http.post('api/articles/' + id + '/featured', { "order_id": orderId }, {
	                headers: {
	                    "Authorization": "Bearer " + this.getToken()
	                }
	            }).then(function (response) {
	                var article = this.lookup[id];

	                //push article on to featured_articles
	                this.featured_articles.push(article);
	            }, function (error) {
	                console.log(error);
	            });
	        },
	        createCategory: function createCategory() {
	            this.$http.post('api/categories', this.category, {
	                headers: {
	                    "Authorization": "Bearer " + this.getToken()
	                }
	            }).then(function (response) {
	                //add to list of categories
	                this.categories.push(response.body);
	                $('#add-category').modal('toggle');
	            }, function (error) {
	                console.log(error);
	            });
	        },
	        onChange: function onChange(object) {
	            if (object.hasOwnProperty("moved")) {
	                this.$http.post('api/articles/' + object.moved.element.id + '/featured', {
	                    "order_id": object.moved.newIndex + 1
	                }, {
	                    headers: {
	                        "Authorization": "Bearer " + this.getToken()
	                    }
	                }).then(function (response) {}, function (error) {
	                    console.log(error);
	                });
	            }
	        },
	        onFileChange: function onFileChange(e) {
	            this.fileFormData.append('image', e.target.files[0]);
	        }
	    }
	});

	new Vue({
	    el: "#vue-navigation",
	    mixins: [auth]
	});

/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	'use strict';

	var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

	function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

	(function () {
	  "use strict";

	  function buildDraggable(Sortable) {
	    function removeNode(node) {
	      node.parentElement.removeChild(node);
	    }

	    function insertNodeAt(fatherNode, node, position) {
	      if (position < fatherNode.children.length) {
	        fatherNode.insertBefore(node, fatherNode.children[position]);
	      } else {
	        fatherNode.appendChild(node);
	      }
	    }

	    function computeVmIndex(vnodes, element) {
	      return vnodes.map(function (elt) {
	        return elt.elm;
	      }).indexOf(element);
	    }

	    function _computeIndexes(slots, children) {
	      return !slots ? [] : Array.prototype.map.call(children, function (elt) {
	        return computeVmIndex(slots, elt);
	      });
	    }

	    function emit(evtName, evtData) {
	      this.$emit(evtName.toLowerCase(), evtData);
	    }

	    function delegateAndEmit(evtName) {
	      var _this = this;

	      return function (evtData) {
	        if (_this.list !== null) {
	          _this['onDrag' + evtName](evtData);
	        }
	        emit.call(_this, evtName, evtData);
	      };
	    }

	    var eventsListened = ['Start', 'Add', 'Remove', 'Update', 'End'];
	    var eventsToEmit = ['Choose', 'Sort', 'Filter', 'Clone'];
	    var readonlyProperties = ['Move'].concat(eventsListened, eventsToEmit).map(function (evt) {
	      return 'on' + evt;
	    });
	    var draggingElement = null;

	    var props = {
	      options: Object,
	      list: {
	        type: Array,
	        required: false,
	        default: null
	      },
	      clone: {
	        type: Function,
	        default: function _default(original) {
	          return original;
	        }
	      },
	      element: {
	        type: String,
	        default: 'div'
	      },
	      move: {
	        type: Function,
	        default: null
	      }
	    };

	    var draggableComponent = {
	      props: props,

	      data: function data() {
	        return {
	          transitionMode: false
	        };
	      },
	      render: function render(h) {
	        if (this.$slots.default && this.$slots.default.length === 1) {
	          var child = this.$slots.default[0];
	          if (child.componentOptions && child.componentOptions.tag === "transition-group") {
	            this.transitionMode = true;
	          }
	        }
	        return h(this.element, null, this.$slots.default);
	      },
	      mounted: function mounted() {
	        var _this2 = this;

	        var optionsAdded = {};
	        eventsListened.forEach(function (elt) {
	          optionsAdded['on' + elt] = delegateAndEmit.call(_this2, elt);
	        });

	        eventsToEmit.forEach(function (elt) {
	          optionsAdded['on' + elt] = emit.bind(_this2, elt);
	        });

	        var options = _extends({}, this.options, optionsAdded, { onMove: function onMove(evt) {
	            return _this2.onDragMove(evt);
	          } });
	        this._sortable = new Sortable(this.rootContainer, options);
	        this.computeIndexes();
	      },
	      beforeDestroy: function beforeDestroy() {
	        this._sortable.destroy();
	      },


	      computed: {
	        rootContainer: function rootContainer() {
	          return this.transitionMode ? this.$el.children[0] : this.$el;
	        }
	      },

	      watch: {
	        options: function options(newOptionValue) {
	          for (var property in newOptionValue) {
	            if (readonlyProperties.indexOf(property) == -1) {
	              this._sortable.option(property, newOptionValue[property]);
	            }
	          }
	        },
	        list: function list() {
	          this.computeIndexes();
	        }
	      },

	      methods: {
	        getChildrenNodes: function getChildrenNodes() {
	          var rawNodes = this.$slots.default;
	          return this.transitionMode ? rawNodes[0].child.$slots.default : rawNodes;
	        },
	        computeIndexes: function computeIndexes() {
	          var _this3 = this;

	          this.$nextTick(function () {
	            _this3.visibleIndexes = _computeIndexes(_this3.getChildrenNodes(), _this3.rootContainer.children);
	          });
	        },
	        getUnderlyingVm: function getUnderlyingVm(htmlElt) {
	          var index = computeVmIndex(this.getChildrenNodes(), htmlElt);
	          var element = this.list[index];
	          return { index: index, element: element };
	        },
	        getUnderlyingPotencialDraggableComponent: function getUnderlyingPotencialDraggableComponent(_ref) {
	          var __vue__ = _ref.__vue__;

	          if (!__vue__ || !__vue__.$options || __vue__.$options._componentTag !== "transition-group") {
	            return __vue__;
	          }
	          return __vue__.$parent;
	        },
	        emitChanges: function emitChanges(evt) {
	          var _this4 = this;

	          this.$nextTick(function () {
	            _this4.$emit('change', evt);
	          });
	        },
	        spliceList: function spliceList() {
	          var _list;

	          (_list = this.list).splice.apply(_list, arguments);
	        },
	        updatePosition: function updatePosition(oldIndex, newIndex) {
	          this.list.splice(newIndex, 0, this.list.splice(oldIndex, 1)[0]);
	        },
	        getRelatedContextFromMoveEvent: function getRelatedContextFromMoveEvent(_ref2) {
	          var to = _ref2.to,
	              related = _ref2.related;

	          var component = this.getUnderlyingPotencialDraggableComponent(to);
	          if (!component) {
	            return { component: component };
	          }
	          var list = component.list;
	          var context = { list: list, component: component };
	          if (to !== related && list && component.getUnderlyingVm) {
	            var destination = component.getUnderlyingVm(related);
	            return _extends(destination, context);
	          }

	          return context;
	        },
	        getVmIndex: function getVmIndex(domIndex) {
	          var indexes = this.visibleIndexes;
	          var numberIndexes = indexes.length;
	          return domIndex > numberIndexes - 1 ? numberIndexes : indexes[domIndex];
	        },
	        onDragStart: function onDragStart(evt) {
	          this.context = this.getUnderlyingVm(evt.item);
	          evt.item._underlying_vm_ = this.clone(this.context.element);
	          draggingElement = evt.item;
	        },
	        onDragAdd: function onDragAdd(evt) {
	          var element = evt.item._underlying_vm_;
	          if (element === undefined) {
	            return;
	          }
	          removeNode(evt.item);
	          var newIndex = this.getVmIndex(evt.newIndex);
	          this.spliceList(newIndex, 0, element);
	          this.computeIndexes();
	          var added = { element: element, newIndex: newIndex };
	          this.emitChanges({ added: added });
	        },
	        onDragRemove: function onDragRemove(evt) {
	          insertNodeAt(this.rootContainer, evt.item, evt.oldIndex);
	          var isCloning = !!evt.clone;
	          if (isCloning) {
	            removeNode(evt.clone);
	            return;
	          }
	          var oldIndex = this.context.index;
	          this.spliceList(oldIndex, 1);
	          var removed = { element: this.context.element, oldIndex: oldIndex };
	          this.emitChanges({ removed: removed });
	        },
	        onDragUpdate: function onDragUpdate(evt) {
	          removeNode(evt.item);
	          insertNodeAt(evt.from, evt.item, evt.oldIndex);
	          var oldIndex = this.context.index;
	          var newIndex = this.getVmIndex(evt.newIndex);
	          this.updatePosition(oldIndex, newIndex);
	          var moved = { element: this.context.element, oldIndex: oldIndex, newIndex: newIndex };
	          this.emitChanges({ moved: moved });
	        },
	        computeFutureIndex: function computeFutureIndex(relatedContext, evt) {
	          if (!relatedContext.element) {
	            return 0;
	          }
	          var domChildren = [].concat(_toConsumableArray(evt.to.children));
	          var currentDOMIndex = domChildren.indexOf(evt.related);
	          var currentIndex = relatedContext.component.getVmIndex(currentDOMIndex);
	          var draggedInList = domChildren.indexOf(draggingElement) != -1;
	          return draggedInList ? currentIndex : currentIndex + 1;
	        },
	        onDragMove: function onDragMove(evt) {
	          var onMove = this.move;
	          if (!onMove || !this.list) {
	            return true;
	          }

	          var relatedContext = this.getRelatedContextFromMoveEvent(evt);
	          var draggedContext = this.context;
	          var futureIndex = this.computeFutureIndex(relatedContext, evt);
	          _extends(draggedContext, { futureIndex: futureIndex });
	          _extends(evt, { relatedContext: relatedContext, draggedContext: draggedContext });
	          return onMove(evt);
	        },
	        onDragEnd: function onDragEnd(evt) {
	          this.computeIndexes();
	          draggingElement = null;
	        }
	      }
	    };
	    return draggableComponent;
	  }

	  if (true) {
	    var Sortable = __webpack_require__(2);
	    module.exports = buildDraggable(Sortable);
	  } else if (typeof define == "function" && define.amd) {
	    define(['sortablejs'], function (Sortable) {
	      return buildDraggable(Sortable);
	    });
	  } else if (window && window.Vue && window.Sortable) {
	    var draggable = buildDraggable(window.Sortable);
	    Vue.component('draggable', draggable);
	  }
	})();

/***/ },
/* 2 */
/***/ function(module, exports, __webpack_require__) {

	var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/**!
	 * Sortable
	 * @author	RubaXa   <trash@rubaxa.org>
	 * @license MIT
	 */

	(function sortableModule(factory) {
		"use strict";

		if (true) {
			!(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory), __WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ? (__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) : __WEBPACK_AMD_DEFINE_FACTORY__), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
		}
		else if (typeof module != "undefined" && typeof module.exports != "undefined") {
			module.exports = factory();
		}
		else {
			/* jshint sub:true */
			window["Sortable"] = factory();
		}
	})(function sortableFactory() {
		"use strict";

		if (typeof window == "undefined" || !window.document) {
			return function sortableError() {
				throw new Error("Sortable.js requires a window with a document");
			};
		}

		var dragEl,
			parentEl,
			ghostEl,
			cloneEl,
			rootEl,
			nextEl,
			lastDownEl,

			scrollEl,
			scrollParentEl,
			scrollCustomFn,

			lastEl,
			lastCSS,
			lastParentCSS,

			oldIndex,
			newIndex,

			activeGroup,
			putSortable,

			autoScroll = {},

			tapEvt,
			touchEvt,

			moved,

			/** @const */
			R_SPACE = /\s+/g,
			R_FLOAT = /left|right|inline/,

			expando = 'Sortable' + (new Date).getTime(),

			win = window,
			document = win.document,
			parseInt = win.parseInt,

			$ = win.jQuery || win.Zepto,
			Polymer = win.Polymer,

			captureMode = false,

			supportDraggable = !!('draggable' in document.createElement('div')),
			supportCssPointerEvents = (function (el) {
				// false when IE11
				if (!!navigator.userAgent.match(/Trident.*rv[ :]?11\./)) {
					return false;
				}
				el = document.createElement('x');
				el.style.cssText = 'pointer-events:auto';
				return el.style.pointerEvents === 'auto';
			})(),

			_silent = false,

			abs = Math.abs,
			min = Math.min,

			savedInputChecked = [],
			touchDragOverListeners = [],

			_autoScroll = _throttle(function (/**Event*/evt, /**Object*/options, /**HTMLElement*/rootEl) {
				// Bug: https://bugzilla.mozilla.org/show_bug.cgi?id=505521
				if (rootEl && options.scroll) {
					var _this = rootEl[expando],
						el,
						rect,
						sens = options.scrollSensitivity,
						speed = options.scrollSpeed,

						x = evt.clientX,
						y = evt.clientY,

						winWidth = window.innerWidth,
						winHeight = window.innerHeight,

						vx,
						vy,

						scrollOffsetX,
						scrollOffsetY
					;

					// Delect scrollEl
					if (scrollParentEl !== rootEl) {
						scrollEl = options.scroll;
						scrollParentEl = rootEl;
						scrollCustomFn = options.scrollFn;

						if (scrollEl === true) {
							scrollEl = rootEl;

							do {
								if ((scrollEl.offsetWidth < scrollEl.scrollWidth) ||
									(scrollEl.offsetHeight < scrollEl.scrollHeight)
								) {
									break;
								}
								/* jshint boss:true */
							} while (scrollEl = scrollEl.parentNode);
						}
					}

					if (scrollEl) {
						el = scrollEl;
						rect = scrollEl.getBoundingClientRect();
						vx = (abs(rect.right - x) <= sens) - (abs(rect.left - x) <= sens);
						vy = (abs(rect.bottom - y) <= sens) - (abs(rect.top - y) <= sens);
					}


					if (!(vx || vy)) {
						vx = (winWidth - x <= sens) - (x <= sens);
						vy = (winHeight - y <= sens) - (y <= sens);

						/* jshint expr:true */
						(vx || vy) && (el = win);
					}


					if (autoScroll.vx !== vx || autoScroll.vy !== vy || autoScroll.el !== el) {
						autoScroll.el = el;
						autoScroll.vx = vx;
						autoScroll.vy = vy;

						clearInterval(autoScroll.pid);

						if (el) {
							autoScroll.pid = setInterval(function () {
								scrollOffsetY = vy ? vy * speed : 0;
								scrollOffsetX = vx ? vx * speed : 0;

								if ('function' === typeof(scrollCustomFn)) {
									return scrollCustomFn.call(_this, scrollOffsetX, scrollOffsetY, evt);
								}

								if (el === win) {
									win.scrollTo(win.pageXOffset + scrollOffsetX, win.pageYOffset + scrollOffsetY);
								} else {
									el.scrollTop += scrollOffsetY;
									el.scrollLeft += scrollOffsetX;
								}
							}, 24);
						}
					}
				}
			}, 30),

			_prepareGroup = function (options) {
				function toFn(value, pull) {
					if (value === void 0 || value === true) {
						value = group.name;
					}

					if (typeof value === 'function') {
						return value;
					} else {
						return function (to, from) {
							var fromGroup = from.options.group.name;

							return pull
								? value
								: value && (value.join
									? value.indexOf(fromGroup) > -1
									: (fromGroup == value)
								);
						};
					}
				}

				var group = {};
				var originalGroup = options.group;

				if (!originalGroup || typeof originalGroup != 'object') {
					originalGroup = {name: originalGroup};
				}

				group.name = originalGroup.name;
				group.checkPull = toFn(originalGroup.pull, true);
				group.checkPut = toFn(originalGroup.put);
				group.revertClone = originalGroup.revertClone;

				options.group = group;
			}
		;


		/**
		 * @class  Sortable
		 * @param  {HTMLElement}  el
		 * @param  {Object}       [options]
		 */
		function Sortable(el, options) {
			if (!(el && el.nodeType && el.nodeType === 1)) {
				throw 'Sortable: `el` must be HTMLElement, and not ' + {}.toString.call(el);
			}

			this.el = el; // root element
			this.options = options = _extend({}, options);


			// Export instance
			el[expando] = this;

			// Default options
			var defaults = {
				group: Math.random(),
				sort: true,
				disabled: false,
				store: null,
				handle: null,
				scroll: true,
				scrollSensitivity: 30,
				scrollSpeed: 10,
				draggable: /[uo]l/i.test(el.nodeName) ? 'li' : '>*',
				ghostClass: 'sortable-ghost',
				chosenClass: 'sortable-chosen',
				dragClass: 'sortable-drag',
				ignore: 'a, img',
				filter: null,
				preventOnFilter: true,
				animation: 0,
				setData: function (dataTransfer, dragEl) {
					dataTransfer.setData('Text', dragEl.textContent);
				},
				dropBubble: false,
				dragoverBubble: false,
				dataIdAttr: 'data-id',
				delay: 0,
				forceFallback: false,
				fallbackClass: 'sortable-fallback',
				fallbackOnBody: false,
				fallbackTolerance: 0,
				fallbackOffset: {x: 0, y: 0}
			};


			// Set default options
			for (var name in defaults) {
				!(name in options) && (options[name] = defaults[name]);
			}

			_prepareGroup(options);

			// Bind all private methods
			for (var fn in this) {
				if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
					this[fn] = this[fn].bind(this);
				}
			}

			// Setup drag mode
			this.nativeDraggable = options.forceFallback ? false : supportDraggable;

			// Bind events
			_on(el, 'mousedown', this._onTapStart);
			_on(el, 'touchstart', this._onTapStart);
			_on(el, 'pointerdown', this._onTapStart);

			if (this.nativeDraggable) {
				_on(el, 'dragover', this);
				_on(el, 'dragenter', this);
			}

			touchDragOverListeners.push(this._onDragOver);

			// Restore sorting
			options.store && this.sort(options.store.get(this));
		}


		Sortable.prototype = /** @lends Sortable.prototype */ {
			constructor: Sortable,

			_onTapStart: function (/** Event|TouchEvent */evt) {
				var _this = this,
					el = this.el,
					options = this.options,
					preventOnFilter = options.preventOnFilter,
					type = evt.type,
					touch = evt.touches && evt.touches[0],
					target = (touch || evt).target,
					originalTarget = evt.target.shadowRoot && evt.path[0] || target,
					filter = options.filter,
					startIndex;

				_saveInputCheckedState(el);


				// Don't trigger start event when an element is been dragged, otherwise the evt.oldindex always wrong when set option.group.
				if (dragEl) {
					return;
				}

				if (type === 'mousedown' && evt.button !== 0 || options.disabled) {
					return; // only left button or enabled
				}


				target = _closest(target, options.draggable, el);

				if (!target) {
					return;
				}

				if (lastDownEl === target) {
					// Ignoring duplicate `down`
					return;
				}

				// Get the index of the dragged element within its parent
				startIndex = _index(target, options.draggable);

				// Check filter
				if (typeof filter === 'function') {
					if (filter.call(this, evt, target, this)) {
						_dispatchEvent(_this, originalTarget, 'filter', target, el, startIndex);
						preventOnFilter && evt.preventDefault();
						return; // cancel dnd
					}
				}
				else if (filter) {
					filter = filter.split(',').some(function (criteria) {
						criteria = _closest(originalTarget, criteria.trim(), el);

						if (criteria) {
							_dispatchEvent(_this, criteria, 'filter', target, el, startIndex);
							return true;
						}
					});

					if (filter) {
						preventOnFilter && evt.preventDefault();
						return; // cancel dnd
					}
				}

				if (options.handle && !_closest(originalTarget, options.handle, el)) {
					return;
				}

				// Prepare `dragstart`
				this._prepareDragStart(evt, touch, target, startIndex);
			},

			_prepareDragStart: function (/** Event */evt, /** Touch */touch, /** HTMLElement */target, /** Number */startIndex) {
				var _this = this,
					el = _this.el,
					options = _this.options,
					ownerDocument = el.ownerDocument,
					dragStartFn;

				if (target && !dragEl && (target.parentNode === el)) {
					tapEvt = evt;

					rootEl = el;
					dragEl = target;
					parentEl = dragEl.parentNode;
					nextEl = dragEl.nextSibling;
					lastDownEl = target;
					activeGroup = options.group;
					oldIndex = startIndex;

					this._lastX = (touch || evt).clientX;
					this._lastY = (touch || evt).clientY;

					dragEl.style['will-change'] = 'transform';

					dragStartFn = function () {
						// Delayed drag has been triggered
						// we can re-enable the events: touchmove/mousemove
						_this._disableDelayedDrag();

						// Make the element draggable
						dragEl.draggable = _this.nativeDraggable;

						// Chosen item
						_toggleClass(dragEl, options.chosenClass, true);

						// Bind the events: dragstart/dragend
						_this._triggerDragStart(evt, touch);

						// Drag start event
						_dispatchEvent(_this, rootEl, 'choose', dragEl, rootEl, oldIndex);
					};

					// Disable "draggable"
					options.ignore.split(',').forEach(function (criteria) {
						_find(dragEl, criteria.trim(), _disableDraggable);
					});

					_on(ownerDocument, 'mouseup', _this._onDrop);
					_on(ownerDocument, 'touchend', _this._onDrop);
					_on(ownerDocument, 'touchcancel', _this._onDrop);
					_on(ownerDocument, 'pointercancel', _this._onDrop);
					_on(ownerDocument, 'selectstart', _this);

					if (options.delay) {
						// If the user moves the pointer or let go the click or touch
						// before the delay has been reached:
						// disable the delayed drag
						_on(ownerDocument, 'mouseup', _this._disableDelayedDrag);
						_on(ownerDocument, 'touchend', _this._disableDelayedDrag);
						_on(ownerDocument, 'touchcancel', _this._disableDelayedDrag);
						_on(ownerDocument, 'mousemove', _this._disableDelayedDrag);
						_on(ownerDocument, 'touchmove', _this._disableDelayedDrag);
						_on(ownerDocument, 'pointermove', _this._disableDelayedDrag);

						_this._dragStartTimer = setTimeout(dragStartFn, options.delay);
					} else {
						dragStartFn();
					}


				}
			},

			_disableDelayedDrag: function () {
				var ownerDocument = this.el.ownerDocument;

				clearTimeout(this._dragStartTimer);
				_off(ownerDocument, 'mouseup', this._disableDelayedDrag);
				_off(ownerDocument, 'touchend', this._disableDelayedDrag);
				_off(ownerDocument, 'touchcancel', this._disableDelayedDrag);
				_off(ownerDocument, 'mousemove', this._disableDelayedDrag);
				_off(ownerDocument, 'touchmove', this._disableDelayedDrag);
				_off(ownerDocument, 'pointermove', this._disableDelayedDrag);
			},

			_triggerDragStart: function (/** Event */evt, /** Touch */touch) {
				touch = touch || (evt.pointerType == 'touch' ? evt : null);

				if (touch) {
					// Touch device support
					tapEvt = {
						target: dragEl,
						clientX: touch.clientX,
						clientY: touch.clientY
					};

					this._onDragStart(tapEvt, 'touch');
				}
				else if (!this.nativeDraggable) {
					this._onDragStart(tapEvt, true);
				}
				else {
					_on(dragEl, 'dragend', this);
					_on(rootEl, 'dragstart', this._onDragStart);
				}

				try {
					if (document.selection) {					
						// Timeout neccessary for IE9					
						setTimeout(function () {
							document.selection.empty();
						});					
					} else {
						window.getSelection().removeAllRanges();
					}
				} catch (err) {
				}
			},

			_dragStarted: function () {
				if (rootEl && dragEl) {
					var options = this.options;

					// Apply effect
					_toggleClass(dragEl, options.ghostClass, true);
					_toggleClass(dragEl, options.dragClass, false);

					Sortable.active = this;

					// Drag start event
					_dispatchEvent(this, rootEl, 'start', dragEl, rootEl, oldIndex);
				} else {
					this._nulling();
				}
			},

			_emulateDragOver: function () {
				if (touchEvt) {
					if (this._lastX === touchEvt.clientX && this._lastY === touchEvt.clientY) {
						return;
					}

					this._lastX = touchEvt.clientX;
					this._lastY = touchEvt.clientY;

					if (!supportCssPointerEvents) {
						_css(ghostEl, 'display', 'none');
					}

					var target = document.elementFromPoint(touchEvt.clientX, touchEvt.clientY),
						parent = target,
						i = touchDragOverListeners.length;

					if (parent) {
						do {
							if (parent[expando]) {
								while (i--) {
									touchDragOverListeners[i]({
										clientX: touchEvt.clientX,
										clientY: touchEvt.clientY,
										target: target,
										rootEl: parent
									});
								}

								break;
							}

							target = parent; // store last element
						}
						/* jshint boss:true */
						while (parent = parent.parentNode);
					}

					if (!supportCssPointerEvents) {
						_css(ghostEl, 'display', '');
					}
				}
			},


			_onTouchMove: function (/**TouchEvent*/evt) {
				if (tapEvt) {
					var	options = this.options,
						fallbackTolerance = options.fallbackTolerance,
						fallbackOffset = options.fallbackOffset,
						touch = evt.touches ? evt.touches[0] : evt,
						dx = (touch.clientX - tapEvt.clientX) + fallbackOffset.x,
						dy = (touch.clientY - tapEvt.clientY) + fallbackOffset.y,
						translate3d = evt.touches ? 'translate3d(' + dx + 'px,' + dy + 'px,0)' : 'translate(' + dx + 'px,' + dy + 'px)';

					// only set the status to dragging, when we are actually dragging
					if (!Sortable.active) {
						if (fallbackTolerance &&
							min(abs(touch.clientX - this._lastX), abs(touch.clientY - this._lastY)) < fallbackTolerance
						) {
							return;
						}

						this._dragStarted();
					}

					// as well as creating the ghost element on the document body
					this._appendGhost();

					moved = true;
					touchEvt = touch;

					_css(ghostEl, 'webkitTransform', translate3d);
					_css(ghostEl, 'mozTransform', translate3d);
					_css(ghostEl, 'msTransform', translate3d);
					_css(ghostEl, 'transform', translate3d);

					evt.preventDefault();
				}
			},

			_appendGhost: function () {
				if (!ghostEl) {
					var rect = dragEl.getBoundingClientRect(),
						css = _css(dragEl),
						options = this.options,
						ghostRect;

					ghostEl = dragEl.cloneNode(true);

					_toggleClass(ghostEl, options.ghostClass, false);
					_toggleClass(ghostEl, options.fallbackClass, true);
					_toggleClass(ghostEl, options.dragClass, true);

					_css(ghostEl, 'top', rect.top - parseInt(css.marginTop, 10));
					_css(ghostEl, 'left', rect.left - parseInt(css.marginLeft, 10));
					_css(ghostEl, 'width', rect.width);
					_css(ghostEl, 'height', rect.height);
					_css(ghostEl, 'opacity', '0.8');
					_css(ghostEl, 'position', 'fixed');
					_css(ghostEl, 'zIndex', '100000');
					_css(ghostEl, 'pointerEvents', 'none');

					options.fallbackOnBody && document.body.appendChild(ghostEl) || rootEl.appendChild(ghostEl);

					// Fixing dimensions.
					ghostRect = ghostEl.getBoundingClientRect();
					_css(ghostEl, 'width', rect.width * 2 - ghostRect.width);
					_css(ghostEl, 'height', rect.height * 2 - ghostRect.height);
				}
			},

			_onDragStart: function (/**Event*/evt, /**boolean*/useFallback) {
				var dataTransfer = evt.dataTransfer,
					options = this.options;

				this._offUpEvents();

				if (activeGroup.checkPull(this, this, dragEl, evt)) {
					cloneEl = _clone(dragEl);

					cloneEl.draggable = false;
					cloneEl.style['will-change'] = '';

					_css(cloneEl, 'display', 'none');
					_toggleClass(cloneEl, this.options.chosenClass, false);

					rootEl.insertBefore(cloneEl, dragEl);
					_dispatchEvent(this, rootEl, 'clone', dragEl);
				}

				_toggleClass(dragEl, options.dragClass, true);

				if (useFallback) {
					if (useFallback === 'touch') {
						// Bind touch events
						_on(document, 'touchmove', this._onTouchMove);
						_on(document, 'touchend', this._onDrop);
						_on(document, 'touchcancel', this._onDrop);
						_on(document, 'pointermove', this._onTouchMove);
						_on(document, 'pointerup', this._onDrop);
					} else {
						// Old brwoser
						_on(document, 'mousemove', this._onTouchMove);
						_on(document, 'mouseup', this._onDrop);
					}

					this._loopId = setInterval(this._emulateDragOver, 50);
				}
				else {
					if (dataTransfer) {
						dataTransfer.effectAllowed = 'move';
						options.setData && options.setData.call(this, dataTransfer, dragEl);
					}

					_on(document, 'drop', this);
					setTimeout(this._dragStarted, 0);
				}
			},

			_onDragOver: function (/**Event*/evt) {
				var el = this.el,
					target,
					dragRect,
					targetRect,
					revert,
					options = this.options,
					group = options.group,
					activeSortable = Sortable.active,
					isOwner = (activeGroup === group),
					isMovingBetweenSortable = false,
					canSort = options.sort;

				if (evt.preventDefault !== void 0) {
					evt.preventDefault();
					!options.dragoverBubble && evt.stopPropagation();
				}

				if (dragEl.animated) {
					return;
				}

				moved = true;

				if (activeSortable && !options.disabled &&
					(isOwner
						? canSort || (revert = !rootEl.contains(dragEl)) // Reverting item into the original list
						: (
							putSortable === this ||
							(
								(activeSortable.lastPullMode = activeGroup.checkPull(this, activeSortable, dragEl, evt)) &&
								group.checkPut(this, activeSortable, dragEl, evt)
							)
						)
					) &&
					(evt.rootEl === void 0 || evt.rootEl === this.el) // touch fallback
				) {
					// Smart auto-scrolling
					_autoScroll(evt, options, this.el);

					if (_silent) {
						return;
					}

					target = _closest(evt.target, options.draggable, el);
					dragRect = dragEl.getBoundingClientRect();

					if (putSortable !== this) {
						putSortable = this;
						isMovingBetweenSortable = true;
					}

					if (revert) {
						_cloneHide(activeSortable, true);
						parentEl = rootEl; // actualization

						if (cloneEl || nextEl) {
							rootEl.insertBefore(dragEl, cloneEl || nextEl);
						}
						else if (!canSort) {
							rootEl.appendChild(dragEl);
						}

						return;
					}


					if ((el.children.length === 0) || (el.children[0] === ghostEl) ||
						(el === evt.target) && (target = _ghostIsLast(el, evt))
					) {
						if (target) {
							if (target.animated) {
								return;
							}

							targetRect = target.getBoundingClientRect();
						}

						_cloneHide(activeSortable, isOwner);

						if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt) !== false) {
							if (!dragEl.contains(el)) {
								el.appendChild(dragEl);
								parentEl = el; // actualization
							}

							this._animate(dragRect, dragEl);
							target && this._animate(targetRect, target);
						}
					}
					else if (target && !target.animated && target !== dragEl && (target.parentNode[expando] !== void 0)) {
						if (lastEl !== target) {
							lastEl = target;
							lastCSS = _css(target);
							lastParentCSS = _css(target.parentNode);
						}

						targetRect = target.getBoundingClientRect();

						var width = targetRect.right - targetRect.left,
							height = targetRect.bottom - targetRect.top,
							floating = R_FLOAT.test(lastCSS.cssFloat + lastCSS.display)
								|| (lastParentCSS.display == 'flex' && lastParentCSS['flex-direction'].indexOf('row') === 0),
							isWide = (target.offsetWidth > dragEl.offsetWidth),
							isLong = (target.offsetHeight > dragEl.offsetHeight),
							halfway = (floating ? (evt.clientX - targetRect.left) / width : (evt.clientY - targetRect.top) / height) > 0.5,
							nextSibling = target.nextElementSibling,
							moveVector = _onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt),
							after = false
						;

						if (moveVector !== false) {
							_silent = true;
							setTimeout(_unsilent, 30);

							_cloneHide(activeSortable, isOwner);

							if (moveVector === 1 || moveVector === -1) {
								after = (moveVector === 1);
							}
							else if (floating) {
								var elTop = dragEl.offsetTop,
									tgTop = target.offsetTop;

								if (elTop === tgTop) {
									after = (target.previousElementSibling === dragEl) && !isWide || halfway && isWide;
								}
								else if (target.previousElementSibling === dragEl || dragEl.previousElementSibling === target) {
									after = (evt.clientY - targetRect.top) / height > 0.5;
								} else {
									after = tgTop > elTop;
								}
							} else if (!isMovingBetweenSortable) {
								after = (nextSibling !== dragEl) && !isLong || halfway && isLong;
							}

							if (!dragEl.contains(el)) {
								if (after && !nextSibling) {
									el.appendChild(dragEl);
								} else {
									target.parentNode.insertBefore(dragEl, after ? nextSibling : target);
								}
							}

							parentEl = dragEl.parentNode; // actualization

							this._animate(dragRect, dragEl);
							this._animate(targetRect, target);
						}
					}
				}
			},

			_animate: function (prevRect, target) {
				var ms = this.options.animation;

				if (ms) {
					var currentRect = target.getBoundingClientRect();

					if (prevRect.nodeType === 1) {
						prevRect = prevRect.getBoundingClientRect();
					}

					_css(target, 'transition', 'none');
					_css(target, 'transform', 'translate3d('
						+ (prevRect.left - currentRect.left) + 'px,'
						+ (prevRect.top - currentRect.top) + 'px,0)'
					);

					target.offsetWidth; // repaint

					_css(target, 'transition', 'all ' + ms + 'ms');
					_css(target, 'transform', 'translate3d(0,0,0)');

					clearTimeout(target.animated);
					target.animated = setTimeout(function () {
						_css(target, 'transition', '');
						_css(target, 'transform', '');
						target.animated = false;
					}, ms);
				}
			},

			_offUpEvents: function () {
				var ownerDocument = this.el.ownerDocument;

				_off(document, 'touchmove', this._onTouchMove);
				_off(document, 'pointermove', this._onTouchMove);
				_off(ownerDocument, 'mouseup', this._onDrop);
				_off(ownerDocument, 'touchend', this._onDrop);
				_off(ownerDocument, 'pointerup', this._onDrop);
				_off(ownerDocument, 'touchcancel', this._onDrop);
				_off(ownerDocument, 'selectstart', this);
			},

			_onDrop: function (/**Event*/evt) {
				var el = this.el,
					options = this.options;

				clearInterval(this._loopId);
				clearInterval(autoScroll.pid);
				clearTimeout(this._dragStartTimer);

				// Unbind events
				_off(document, 'mousemove', this._onTouchMove);

				if (this.nativeDraggable) {
					_off(document, 'drop', this);
					_off(el, 'dragstart', this._onDragStart);
				}

				this._offUpEvents();

				if (evt) {
					if (moved) {
						evt.preventDefault();
						!options.dropBubble && evt.stopPropagation();
					}

					ghostEl && ghostEl.parentNode.removeChild(ghostEl);

					if (dragEl) {
						if (this.nativeDraggable) {
							_off(dragEl, 'dragend', this);
						}

						_disableDraggable(dragEl);
						dragEl.style['will-change'] = '';

						// Remove class's
						_toggleClass(dragEl, this.options.ghostClass, false);
						_toggleClass(dragEl, this.options.chosenClass, false);

						if (rootEl !== parentEl) {
							newIndex = _index(dragEl, options.draggable);

							if (newIndex >= 0) {

								// Add event
								_dispatchEvent(null, parentEl, 'add', dragEl, rootEl, oldIndex, newIndex);

								// Remove event
								_dispatchEvent(this, rootEl, 'remove', dragEl, rootEl, oldIndex, newIndex);

								// drag from one list and drop into another
								_dispatchEvent(null, parentEl, 'sort', dragEl, rootEl, oldIndex, newIndex);
								_dispatchEvent(this, rootEl, 'sort', dragEl, rootEl, oldIndex, newIndex);
							}
						}
						else {
							// Remove clone
							cloneEl && cloneEl.parentNode.removeChild(cloneEl);

							if (dragEl.nextSibling !== nextEl) {
								// Get the index of the dragged element within its parent
								newIndex = _index(dragEl, options.draggable);

								if (newIndex >= 0) {
									// drag & drop within the same list
									_dispatchEvent(this, rootEl, 'update', dragEl, rootEl, oldIndex, newIndex);
									_dispatchEvent(this, rootEl, 'sort', dragEl, rootEl, oldIndex, newIndex);
								}
							}
						}

						if (Sortable.active) {
							/* jshint eqnull:true */
							if (newIndex == null || newIndex === -1) {
								newIndex = oldIndex;
							}

							_dispatchEvent(this, rootEl, 'end', dragEl, rootEl, oldIndex, newIndex);

							// Save sorting
							this.save();
						}
					}

				}

				this._nulling();
			},

			_nulling: function() {
				rootEl =
				dragEl =
				parentEl =
				ghostEl =
				nextEl =
				cloneEl =
				lastDownEl =

				scrollEl =
				scrollParentEl =

				tapEvt =
				touchEvt =

				moved =
				newIndex =

				lastEl =
				lastCSS =

				putSortable =
				activeGroup =
				Sortable.active = null;

				savedInputChecked.forEach(function (el) {
					el.checked = true;
				});
				savedInputChecked.length = 0;
			},

			handleEvent: function (/**Event*/evt) {
				switch (evt.type) {
					case 'drop':
					case 'dragend':
						this._onDrop(evt);
						break;

					case 'dragover':
					case 'dragenter':
						if (dragEl) {
							this._onDragOver(evt);
							_globalDragOver(evt);
						}
						break;

					case 'selectstart':
						evt.preventDefault();
						break;
				}
			},


			/**
			 * Serializes the item into an array of string.
			 * @returns {String[]}
			 */
			toArray: function () {
				var order = [],
					el,
					children = this.el.children,
					i = 0,
					n = children.length,
					options = this.options;

				for (; i < n; i++) {
					el = children[i];
					if (_closest(el, options.draggable, this.el)) {
						order.push(el.getAttribute(options.dataIdAttr) || _generateId(el));
					}
				}

				return order;
			},


			/**
			 * Sorts the elements according to the array.
			 * @param  {String[]}  order  order of the items
			 */
			sort: function (order) {
				var items = {}, rootEl = this.el;

				this.toArray().forEach(function (id, i) {
					var el = rootEl.children[i];

					if (_closest(el, this.options.draggable, rootEl)) {
						items[id] = el;
					}
				}, this);

				order.forEach(function (id) {
					if (items[id]) {
						rootEl.removeChild(items[id]);
						rootEl.appendChild(items[id]);
					}
				});
			},


			/**
			 * Save the current sorting
			 */
			save: function () {
				var store = this.options.store;
				store && store.set(this);
			},


			/**
			 * For each element in the set, get the first element that matches the selector by testing the element itself and traversing up through its ancestors in the DOM tree.
			 * @param   {HTMLElement}  el
			 * @param   {String}       [selector]  default: `options.draggable`
			 * @returns {HTMLElement|null}
			 */
			closest: function (el, selector) {
				return _closest(el, selector || this.options.draggable, this.el);
			},


			/**
			 * Set/get option
			 * @param   {string} name
			 * @param   {*}      [value]
			 * @returns {*}
			 */
			option: function (name, value) {
				var options = this.options;

				if (value === void 0) {
					return options[name];
				} else {
					options[name] = value;

					if (name === 'group') {
						_prepareGroup(options);
					}
				}
			},


			/**
			 * Destroy
			 */
			destroy: function () {
				var el = this.el;

				el[expando] = null;

				_off(el, 'mousedown', this._onTapStart);
				_off(el, 'touchstart', this._onTapStart);
				_off(el, 'pointerdown', this._onTapStart);

				if (this.nativeDraggable) {
					_off(el, 'dragover', this);
					_off(el, 'dragenter', this);
				}

				// Remove draggable attributes
				Array.prototype.forEach.call(el.querySelectorAll('[draggable]'), function (el) {
					el.removeAttribute('draggable');
				});

				touchDragOverListeners.splice(touchDragOverListeners.indexOf(this._onDragOver), 1);

				this._onDrop();

				this.el = el = null;
			}
		};


		function _cloneHide(sortable, state) {
			if (sortable.lastPullMode !== 'clone') {
				state = true;
			}

			if (cloneEl && (cloneEl.state !== state)) {
				_css(cloneEl, 'display', state ? 'none' : '');

				if (!state) {
					if (cloneEl.state) {
						if (sortable.options.group.revertClone) {
							rootEl.insertBefore(cloneEl, nextEl);
							sortable._animate(dragEl, cloneEl);
						} else {
							rootEl.insertBefore(cloneEl, dragEl);
						}
					}
				}

				cloneEl.state = state;
			}
		}


		function _closest(/**HTMLElement*/el, /**String*/selector, /**HTMLElement*/ctx) {
			if (el) {
				ctx = ctx || document;

				do {
					if ((selector === '>*' && el.parentNode === ctx) || _matches(el, selector)) {
						return el;
					}
					/* jshint boss:true */
				} while (el = _getParentOrHost(el));
			}

			return null;
		}


		function _getParentOrHost(el) {
			var parent = el.host;

			return (parent && parent.nodeType) ? parent : el.parentNode;
		}


		function _globalDragOver(/**Event*/evt) {
			if (evt.dataTransfer) {
				evt.dataTransfer.dropEffect = 'move';
			}
			evt.preventDefault();
		}


		function _on(el, event, fn) {
			el.addEventListener(event, fn, captureMode);
		}


		function _off(el, event, fn) {
			el.removeEventListener(event, fn, captureMode);
		}


		function _toggleClass(el, name, state) {
			if (el) {
				if (el.classList) {
					el.classList[state ? 'add' : 'remove'](name);
				}
				else {
					var className = (' ' + el.className + ' ').replace(R_SPACE, ' ').replace(' ' + name + ' ', ' ');
					el.className = (className + (state ? ' ' + name : '')).replace(R_SPACE, ' ');
				}
			}
		}


		function _css(el, prop, val) {
			var style = el && el.style;

			if (style) {
				if (val === void 0) {
					if (document.defaultView && document.defaultView.getComputedStyle) {
						val = document.defaultView.getComputedStyle(el, '');
					}
					else if (el.currentStyle) {
						val = el.currentStyle;
					}

					return prop === void 0 ? val : val[prop];
				}
				else {
					if (!(prop in style)) {
						prop = '-webkit-' + prop;
					}

					style[prop] = val + (typeof val === 'string' ? '' : 'px');
				}
			}
		}


		function _find(ctx, tagName, iterator) {
			if (ctx) {
				var list = ctx.getElementsByTagName(tagName), i = 0, n = list.length;

				if (iterator) {
					for (; i < n; i++) {
						iterator(list[i], i);
					}
				}

				return list;
			}

			return [];
		}



		function _dispatchEvent(sortable, rootEl, name, targetEl, fromEl, startIndex, newIndex) {
			sortable = (sortable || rootEl[expando]);

			var evt = document.createEvent('Event'),
				options = sortable.options,
				onName = 'on' + name.charAt(0).toUpperCase() + name.substr(1);

			evt.initEvent(name, true, true);

			evt.to = rootEl;
			evt.from = fromEl || rootEl;
			evt.item = targetEl || rootEl;
			evt.clone = cloneEl;

			evt.oldIndex = startIndex;
			evt.newIndex = newIndex;

			rootEl.dispatchEvent(evt);

			if (options[onName]) {
				options[onName].call(sortable, evt);
			}
		}


		function _onMove(fromEl, toEl, dragEl, dragRect, targetEl, targetRect, originalEvt) {
			var evt,
				sortable = fromEl[expando],
				onMoveFn = sortable.options.onMove,
				retVal;

			evt = document.createEvent('Event');
			evt.initEvent('move', true, true);

			evt.to = toEl;
			evt.from = fromEl;
			evt.dragged = dragEl;
			evt.draggedRect = dragRect;
			evt.related = targetEl || toEl;
			evt.relatedRect = targetRect || toEl.getBoundingClientRect();

			fromEl.dispatchEvent(evt);

			if (onMoveFn) {
				retVal = onMoveFn.call(sortable, evt, originalEvt);
			}

			return retVal;
		}


		function _disableDraggable(el) {
			el.draggable = false;
		}


		function _unsilent() {
			_silent = false;
		}


		/** @returns {HTMLElement|false} */
		function _ghostIsLast(el, evt) {
			var lastEl = el.lastElementChild,
				rect = lastEl.getBoundingClientRect();

			// 5 — min delta
			// abs — нельзя добавлять, а то глюки при наведении сверху
			return (
				(evt.clientY - (rect.top + rect.height) > 5) ||
				(evt.clientX - (rect.right + rect.width) > 5)
			) && lastEl;
		}


		/**
		 * Generate id
		 * @param   {HTMLElement} el
		 * @returns {String}
		 * @private
		 */
		function _generateId(el) {
			var str = el.tagName + el.className + el.src + el.href + el.textContent,
				i = str.length,
				sum = 0;

			while (i--) {
				sum += str.charCodeAt(i);
			}

			return sum.toString(36);
		}

		/**
		 * Returns the index of an element within its parent for a selected set of
		 * elements
		 * @param  {HTMLElement} el
		 * @param  {selector} selector
		 * @return {number}
		 */
		function _index(el, selector) {
			var index = 0;

			if (!el || !el.parentNode) {
				return -1;
			}

			while (el && (el = el.previousElementSibling)) {
				if ((el.nodeName.toUpperCase() !== 'TEMPLATE') && (selector === '>*' || _matches(el, selector))) {
					index++;
				}
			}

			return index;
		}

		function _matches(/**HTMLElement*/el, /**String*/selector) {
			if (el) {
				selector = selector.split('.');

				var tag = selector.shift().toUpperCase(),
					re = new RegExp('\\s(' + selector.join('|') + ')(?=\\s)', 'g');

				return (
					(tag === '' || el.nodeName.toUpperCase() == tag) &&
					(!selector.length || ((' ' + el.className + ' ').match(re) || []).length == selector.length)
				);
			}

			return false;
		}

		function _throttle(callback, ms) {
			var args, _this;

			return function () {
				if (args === void 0) {
					args = arguments;
					_this = this;

					setTimeout(function () {
						if (args.length === 1) {
							callback.call(_this, args[0]);
						} else {
							callback.apply(_this, args);
						}

						args = void 0;
					}, ms);
				}
			};
		}

		function _extend(dst, src) {
			if (dst && src) {
				for (var key in src) {
					if (src.hasOwnProperty(key)) {
						dst[key] = src[key];
					}
				}
			}

			return dst;
		}

		function _clone(el) {
			return $
				? $(el).clone(true)[0]
				: (Polymer && Polymer.dom
					? Polymer.dom(el).cloneNode(true)
					: el.cloneNode(true)
				);
		}

		function _saveInputCheckedState(root) {
			var inputs = root.getElementsByTagName('input');
			var idx = inputs.length;

			while (idx--) {
				var el = inputs[idx];
				el.checked && savedInputChecked.push(el);
			}
		}

		// Fixed #973: 
		_on(document, 'touchmove', function (evt) {
			if (Sortable.active) {
				evt.preventDefault();
			}
		});

		try {
			window.addEventListener('test', null, Object.defineProperty({}, 'passive', {
				get: function () {
					captureMode = {
						capture: false,
						passive: false
					};
				}
			}));
		} catch (err) {}

		// Export utils
		Sortable.utils = {
			on: _on,
			off: _off,
			css: _css,
			find: _find,
			is: function (el, selector) {
				return !!_closest(el, selector, el);
			},
			extend: _extend,
			throttle: _throttle,
			closest: _closest,
			toggleClass: _toggleClass,
			clone: _clone,
			index: _index
		};


		/**
		 * Create sortable instance
		 * @param {HTMLElement}  el
		 * @param {Object}      [options]
		 */
		Sortable.create = function (el, options) {
			return new Sortable(el, options);
		};


		// Export
		Sortable.version = '1.5.0';
		return Sortable;
	});


/***/ }
/******/ ]);