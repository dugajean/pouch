
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:Pouch" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Pouch.html">Pouch</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Pouch_Exceptions" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Pouch/Exceptions.html">Exceptions</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Pouch_Exceptions_NotFoundException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Exceptions/NotFoundException.html">NotFoundException</a>                    </div>                </li>                            <li data-name="class:Pouch_Exceptions_PouchException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Exceptions/PouchException.html">PouchException</a>                    </div>                </li>                            <li data-name="class:Pouch_Exceptions_ResolvableException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Exceptions/ResolvableException.html">ResolvableException</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Pouch_Helpers" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Pouch/Helpers.html">Helpers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Pouch_Helpers_ClassTree" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Helpers/ClassTree.html">ClassTree</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Pouch_Pouch" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Pouch/Pouch.html">Pouch</a>                    </div>                </li>                            <li data-name="class:Pouch_Resolvable" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Pouch/Resolvable.html">Resolvable</a>                    </div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "Pouch.html", "name": "Pouch", "doc": "Namespace Pouch"},{"type": "Namespace", "link": "Pouch/Exceptions.html", "name": "Pouch\\Exceptions", "doc": "Namespace Pouch\\Exceptions"},{"type": "Namespace", "link": "Pouch/Helpers.html", "name": "Pouch\\Helpers", "doc": "Namespace Pouch\\Helpers"},
            
            {"type": "Class", "fromName": "Pouch\\Exceptions", "fromLink": "Pouch/Exceptions.html", "link": "Pouch/Exceptions/NotFoundException.html", "name": "Pouch\\Exceptions\\NotFoundException", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Pouch\\Exceptions", "fromLink": "Pouch/Exceptions.html", "link": "Pouch/Exceptions/PouchException.html", "name": "Pouch\\Exceptions\\PouchException", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Pouch\\Exceptions", "fromLink": "Pouch/Exceptions.html", "link": "Pouch/Exceptions/ResolvableException.html", "name": "Pouch\\Exceptions\\ResolvableException", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Pouch\\Helpers", "fromLink": "Pouch/Helpers.html", "link": "Pouch/Helpers/ClassTree.html", "name": "Pouch\\Helpers\\ClassTree", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Helpers\\ClassTree", "fromLink": "Pouch/Helpers/ClassTree.html", "link": "Pouch/Helpers/ClassTree.html#method_setRoot", "name": "Pouch\\Helpers\\ClassTree::setRoot", "doc": "&quot;Set the application&#039;s root.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\ClassTree", "fromLink": "Pouch/Helpers/ClassTree.html", "link": "Pouch/Helpers/ClassTree.html#method_loadDev", "name": "Pouch\\Helpers\\ClassTree::loadDev", "doc": "&quot;Include autoload-dev in the results or not.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\ClassTree", "fromLink": "Pouch/Helpers/ClassTree.html", "link": "Pouch/Helpers/ClassTree.html#method_getClassesInNamespace", "name": "Pouch\\Helpers\\ClassTree::getClassesInNamespace", "doc": "&quot;Get all namespaces recursively for a namespace.&quot;"},
            
            {"type": "Class", "fromName": "Pouch", "fromLink": "Pouch.html", "link": "Pouch/Pouch.html", "name": "Pouch\\Pouch", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_bootstrap", "name": "Pouch\\Pouch::bootstrap", "doc": "&quot;Bootstrap pouch.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_bind", "name": "Pouch\\Pouch::bind", "doc": "&quot;Bind a new element to the replaceables.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_registerNamespaces", "name": "Pouch\\Pouch::registerNamespaces", "doc": "&quot;Register one or more namespaces for automatic resolution.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_resolve", "name": "Pouch\\Pouch::resolve", "doc": "&quot;Resolve specific key from our replaceables.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_contains", "name": "Pouch\\Pouch::contains", "doc": "&quot;See if specific key exists in our replaceables.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_get", "name": "Pouch\\Pouch::get", "doc": "&quot;Alias for resolve.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_has", "name": "Pouch\\Pouch::has", "doc": "&quot;Alias for contains.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method___call", "name": "Pouch\\Pouch::__call", "doc": "&quot;To allows for calling the methods of this class statically (via singleton),\nthis class&#039;s methods have to be set to protected. Then we use __call\nin order to call the protected methods normally from the singleton\ninstance and everything ends up wired up perfectly.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_singleton", "name": "Pouch\\Pouch::singleton", "doc": "&quot;Insert or return a singleton instance from our container.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method___callStatic", "name": "Pouch\\Pouch::__callStatic", "doc": "&quot;Allow calling all the methods of this class statically.&quot;"},
            
            {"type": "Class", "fromName": "Pouch", "fromLink": "Pouch.html", "link": "Pouch/Resolvable.html", "name": "Pouch\\Resolvable", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method___construct", "name": "Pouch\\Resolvable::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_make", "name": "Pouch\\Resolvable::make", "doc": "&quot;Set the object for the resolvable. Also resolve constructor dependencies if needed.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_getObject", "name": "Pouch\\Resolvable::getObject", "doc": "&quot;Return the current object of this instance.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_getType", "name": "Pouch\\Resolvable::getType", "doc": "&quot;Get the type of an \&quot;element\&quot; accurately. If it&#039;s an object, the exact class name will be returned.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method___call", "name": "Pouch\\Resolvable::__call", "doc": "&quot;Magic __call method to handle the automatic resolution of parameters.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_resolveDependencies", "name": "Pouch\\Resolvable::resolveDependencies", "doc": "&quot;Resolve the dependency for all parameters.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_createClassDependency", "name": "Pouch\\Resolvable::createClassDependency", "doc": "&quot;Creates missing class if it can be found in the container. Auto-injecting classes from within\nthe container require them to be prefixed with \\Pouch\\Key.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_getContent", "name": "Pouch\\Resolvable::getContent", "doc": "&quot;&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});

