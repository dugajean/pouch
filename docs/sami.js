
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:Pouch" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Pouch.html">Pouch</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Pouch_Cache" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Pouch/Cache.html">Cache</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Pouch_Cache_ApcuCache" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Cache/ApcuCache.html">ApcuCache</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Pouch_Container" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Pouch/Container.html">Container</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Pouch_Container_Item" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Container/Item.html">Item</a>                    </div>                </li>                            <li data-name="class:Pouch_Container_ItemInterface" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Container/ItemInterface.html">ItemInterface</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Pouch_Exceptions" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Pouch/Exceptions.html">Exceptions</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Pouch_Exceptions_InvalidArgumentException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Exceptions/InvalidArgumentException.html">InvalidArgumentException</a>                    </div>                </li>                            <li data-name="class:Pouch_Exceptions_NotFoundException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Exceptions/NotFoundException.html">NotFoundException</a>                    </div>                </li>                            <li data-name="class:Pouch_Exceptions_PouchException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Exceptions/PouchException.html">PouchException</a>                    </div>                </li>                            <li data-name="class:Pouch_Exceptions_ResolvableException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Exceptions/ResolvableException.html">ResolvableException</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Pouch_Helpers" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Pouch/Helpers.html">Helpers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Pouch_Helpers_AliasTrait" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Helpers/AliasTrait.html">AliasTrait</a>                    </div>                </li>                            <li data-name="class:Pouch_Helpers_CacheTrait" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Helpers/CacheTrait.html">CacheTrait</a>                    </div>                </li>                            <li data-name="class:Pouch_Helpers_ClassTree" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Helpers/ClassTree.html">ClassTree</a>                    </div>                </li>                            <li data-name="class:Pouch_Helpers_FactoryTrait" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Helpers/FactoryTrait.html">FactoryTrait</a>                    </div>                </li>                            <li data-name="class:Pouch_Helpers_HookManager" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Pouch/Helpers/HookManager.html">HookManager</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Pouch_Pouch" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Pouch/Pouch.html">Pouch</a>                    </div>                </li>                            <li data-name="class:Pouch_Resolvable" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Pouch/Resolvable.html">Resolvable</a>                    </div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "Pouch.html", "name": "Pouch", "doc": "Namespace Pouch"},{"type": "Namespace", "link": "Pouch/Cache.html", "name": "Pouch\\Cache", "doc": "Namespace Pouch\\Cache"},{"type": "Namespace", "link": "Pouch/Container.html", "name": "Pouch\\Container", "doc": "Namespace Pouch\\Container"},{"type": "Namespace", "link": "Pouch/Exceptions.html", "name": "Pouch\\Exceptions", "doc": "Namespace Pouch\\Exceptions"},{"type": "Namespace", "link": "Pouch/Helpers.html", "name": "Pouch\\Helpers", "doc": "Namespace Pouch\\Helpers"},
            {"type": "Interface", "fromName": "Pouch\\Container", "fromLink": "Pouch/Container.html", "link": "Pouch/Container/ItemInterface.html", "name": "Pouch\\Container\\ItemInterface", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Container\\ItemInterface", "fromLink": "Pouch/Container/ItemInterface.html", "link": "Pouch/Container/ItemInterface.html#method_getName", "name": "Pouch\\Container\\ItemInterface::getName", "doc": "&quot;Returns the class name of the container.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\ItemInterface", "fromLink": "Pouch/Container/ItemInterface.html", "link": "Pouch/Container/ItemInterface.html#method_getContent", "name": "Pouch\\Container\\ItemInterface::getContent", "doc": "&quot;Returns the contents of the container.&quot;"},
            
            
            {"type": "Class", "fromName": "Pouch\\Cache", "fromLink": "Pouch/Cache.html", "link": "Pouch/Cache/ApcuCache.html", "name": "Pouch\\Cache\\ApcuCache", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Cache\\ApcuCache", "fromLink": "Pouch/Cache/ApcuCache.html", "link": "Pouch/Cache/ApcuCache.html#method_factory", "name": "Pouch\\Cache\\ApcuCache::factory", "doc": "&quot;Returns an instance of self.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Cache\\ApcuCache", "fromLink": "Pouch/Cache/ApcuCache.html", "link": "Pouch/Cache/ApcuCache.html#method_get", "name": "Pouch\\Cache\\ApcuCache::get", "doc": "&quot;Fetches a value from the cache.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Cache\\ApcuCache", "fromLink": "Pouch/Cache/ApcuCache.html", "link": "Pouch/Cache/ApcuCache.html#method_set", "name": "Pouch\\Cache\\ApcuCache::set", "doc": "&quot;Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Cache\\ApcuCache", "fromLink": "Pouch/Cache/ApcuCache.html", "link": "Pouch/Cache/ApcuCache.html#method_delete", "name": "Pouch\\Cache\\ApcuCache::delete", "doc": "&quot;Delete an item from the cache by its unique key.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Cache\\ApcuCache", "fromLink": "Pouch/Cache/ApcuCache.html", "link": "Pouch/Cache/ApcuCache.html#method_clear", "name": "Pouch\\Cache\\ApcuCache::clear", "doc": "&quot;Wipes clean the entire cache&#039;s keys.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Cache\\ApcuCache", "fromLink": "Pouch/Cache/ApcuCache.html", "link": "Pouch/Cache/ApcuCache.html#method_getMultiple", "name": "Pouch\\Cache\\ApcuCache::getMultiple", "doc": "&quot;Obtains multiple cache items by their unique keys.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Cache\\ApcuCache", "fromLink": "Pouch/Cache/ApcuCache.html", "link": "Pouch/Cache/ApcuCache.html#method_setMultiple", "name": "Pouch\\Cache\\ApcuCache::setMultiple", "doc": "&quot;Persists a set of key =&gt; value pairs in the cache, with an optional TTL.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Cache\\ApcuCache", "fromLink": "Pouch/Cache/ApcuCache.html", "link": "Pouch/Cache/ApcuCache.html#method_deleteMultiple", "name": "Pouch\\Cache\\ApcuCache::deleteMultiple", "doc": "&quot;Deletes multiple cache items in a single operation.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Cache\\ApcuCache", "fromLink": "Pouch/Cache/ApcuCache.html", "link": "Pouch/Cache/ApcuCache.html#method_has", "name": "Pouch\\Cache\\ApcuCache::has", "doc": "&quot;Determines whether an item is present in the cache.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Cache\\ApcuCache", "fromLink": "Pouch/Cache/ApcuCache.html", "link": "Pouch/Cache/ApcuCache.html#method_enabled", "name": "Pouch\\Cache\\ApcuCache::enabled", "doc": "&quot;Verify APCu is installed and available.&quot;"},
            
            {"type": "Class", "fromName": "Pouch\\Container", "fromLink": "Pouch/Container.html", "link": "Pouch/Container/Item.html", "name": "Pouch\\Container\\Item", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method___construct", "name": "Pouch\\Container\\Item::__construct", "doc": "&quot;Item constructor.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method_getName", "name": "Pouch\\Container\\Item::getName", "doc": "&quot;Returns the key of this item.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method_setName", "name": "Pouch\\Container\\Item::setName", "doc": "&quot;Set the name of this item.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method_getRaw", "name": "Pouch\\Container\\Item::getRaw", "doc": "&quot;Returns the raw closure, non-executed.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method_isResolvedByName", "name": "Pouch\\Container\\Item::isResolvedByName", "doc": "&quot;Returns whether this item can be resolved without typehint and\ninstead uses its name for being resolved.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method_setResolvedByName", "name": "Pouch\\Container\\Item::setResolvedByName", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method_isFactory", "name": "Pouch\\Container\\Item::isFactory", "doc": "&quot;Whether or not this item is a factory.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method_setFactory", "name": "Pouch\\Container\\Item::setFactory", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method_setFactoryArgs", "name": "Pouch\\Container\\Item::setFactoryArgs", "doc": "&quot;Set the arguments to instantiate the factory with.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method_getContent", "name": "Pouch\\Container\\Item::getContent", "doc": "&quot;Returns the contents of the container.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\Item", "fromLink": "Pouch/Container/Item.html", "link": "Pouch/Container/Item.html#method___toString", "name": "Pouch\\Container\\Item::__toString", "doc": "&quot;String representation of an item.&quot;"},
            
            {"type": "Class", "fromName": "Pouch\\Container", "fromLink": "Pouch/Container.html", "link": "Pouch/Container/ItemInterface.html", "name": "Pouch\\Container\\ItemInterface", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Container\\ItemInterface", "fromLink": "Pouch/Container/ItemInterface.html", "link": "Pouch/Container/ItemInterface.html#method_getName", "name": "Pouch\\Container\\ItemInterface::getName", "doc": "&quot;Returns the class name of the container.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Container\\ItemInterface", "fromLink": "Pouch/Container/ItemInterface.html", "link": "Pouch/Container/ItemInterface.html#method_getContent", "name": "Pouch\\Container\\ItemInterface::getContent", "doc": "&quot;Returns the contents of the container.&quot;"},
            
            {"type": "Class", "fromName": "Pouch\\Exceptions", "fromLink": "Pouch/Exceptions.html", "link": "Pouch/Exceptions/InvalidArgumentException.html", "name": "Pouch\\Exceptions\\InvalidArgumentException", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Pouch\\Exceptions", "fromLink": "Pouch/Exceptions.html", "link": "Pouch/Exceptions/NotFoundException.html", "name": "Pouch\\Exceptions\\NotFoundException", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Pouch\\Exceptions", "fromLink": "Pouch/Exceptions.html", "link": "Pouch/Exceptions/PouchException.html", "name": "Pouch\\Exceptions\\PouchException", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Pouch\\Exceptions", "fromLink": "Pouch/Exceptions.html", "link": "Pouch/Exceptions/ResolvableException.html", "name": "Pouch\\Exceptions\\ResolvableException", "doc": "&quot;&quot;"},
                    
            {"type": "Trait", "fromName": "Pouch\\Helpers", "fromLink": "Pouch/Helpers.html", "link": "Pouch/Helpers/AliasTrait.html", "name": "Pouch\\Helpers\\AliasTrait", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Helpers\\AliasTrait", "fromLink": "Pouch/Helpers/AliasTrait.html", "link": "Pouch/Helpers/AliasTrait.html#method_register", "name": "Pouch\\Helpers\\AliasTrait::register", "doc": "&quot;Alias for bind.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\AliasTrait", "fromLink": "Pouch/Helpers/AliasTrait.html", "link": "Pouch/Helpers/AliasTrait.html#method_set", "name": "Pouch\\Helpers\\AliasTrait::set", "doc": "&quot;Alias for bind.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\AliasTrait", "fromLink": "Pouch/Helpers/AliasTrait.html", "link": "Pouch/Helpers/AliasTrait.html#method_resolve", "name": "Pouch\\Helpers\\AliasTrait::resolve", "doc": "&quot;Fetches from container with getContent.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\AliasTrait", "fromLink": "Pouch/Helpers/AliasTrait.html", "link": "Pouch/Helpers/AliasTrait.html#method_contains", "name": "Pouch\\Helpers\\AliasTrait::contains", "doc": "&quot;Alias for has.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\AliasTrait", "fromLink": "Pouch/Helpers/AliasTrait.html", "link": "Pouch/Helpers/AliasTrait.html#method___get", "name": "Pouch\\Helpers\\AliasTrait::__get", "doc": "&quot;Allow retrieving container values via magic properties.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\AliasTrait", "fromLink": "Pouch/Helpers/AliasTrait.html", "link": "Pouch/Helpers/AliasTrait.html#method___isset", "name": "Pouch\\Helpers\\AliasTrait::__isset", "doc": "&quot;Allows the use of isset() to determine if something exists in the container.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\AliasTrait", "fromLink": "Pouch/Helpers/AliasTrait.html", "link": "Pouch/Helpers/AliasTrait.html#method___unset", "name": "Pouch\\Helpers\\AliasTrait::__unset", "doc": "&quot;Allows the use of unset() to remove key a key from the container.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\AliasTrait", "fromLink": "Pouch/Helpers/AliasTrait.html", "link": "Pouch/Helpers/AliasTrait.html#method___call", "name": "Pouch\\Helpers\\AliasTrait::__call", "doc": "&quot;Bind a new key or fetch an existing one if no argument is provided.&quot;"},
            
            {"type": "Trait", "fromName": "Pouch\\Helpers", "fromLink": "Pouch/Helpers.html", "link": "Pouch/Helpers/CacheTrait.html", "name": "Pouch\\Helpers\\CacheTrait", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Helpers\\CacheTrait", "fromLink": "Pouch/Helpers/CacheTrait.html", "link": "Pouch/Helpers/CacheTrait.html#method_cache", "name": "Pouch\\Helpers\\CacheTrait::cache", "doc": "&quot;Helper to retrieve data from cache store.&quot;"},
            
            {"type": "Class", "fromName": "Pouch\\Helpers", "fromLink": "Pouch/Helpers.html", "link": "Pouch/Helpers/ClassTree.html", "name": "Pouch\\Helpers\\ClassTree", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Helpers\\ClassTree", "fromLink": "Pouch/Helpers/ClassTree.html", "link": "Pouch/Helpers/ClassTree.html#method_bootstrap", "name": "Pouch\\Helpers\\ClassTree::bootstrap", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\ClassTree", "fromLink": "Pouch/Helpers/ClassTree.html", "link": "Pouch/Helpers/ClassTree.html#method_unfold", "name": "Pouch\\Helpers\\ClassTree::unfold", "doc": "&quot;Get all sub-namespaces recursively for a namespace.&quot;"},
            
            {"type": "Trait", "fromName": "Pouch\\Helpers", "fromLink": "Pouch/Helpers.html", "link": "Pouch/Helpers/FactoryTrait.html", "name": "Pouch\\Helpers\\FactoryTrait", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Helpers\\FactoryTrait", "fromLink": "Pouch/Helpers/FactoryTrait.html", "link": "Pouch/Helpers/FactoryTrait.html#method_factory", "name": "Pouch\\Helpers\\FactoryTrait::factory", "doc": "&quot;Set factory for upcoming bind or create a factory callable.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\FactoryTrait", "fromLink": "Pouch/Helpers/FactoryTrait.html", "link": "Pouch/Helpers/FactoryTrait.html#method_withArgs", "name": "Pouch\\Helpers\\FactoryTrait::withArgs", "doc": "&quot;Set the args to construct the factory with.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\FactoryTrait", "fromLink": "Pouch/Helpers/FactoryTrait.html", "link": "Pouch/Helpers/FactoryTrait.html#method_setFactoryArgs", "name": "Pouch\\Helpers\\FactoryTrait::setFactoryArgs", "doc": "&quot;Set the arguments during fetch-time.&quot;"},
            
            {"type": "Class", "fromName": "Pouch\\Helpers", "fromLink": "Pouch/Helpers.html", "link": "Pouch/Helpers/HookManager.html", "name": "Pouch\\Helpers\\HookManager", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_factory", "name": "Pouch\\Helpers\\HookManager::factory", "doc": "&quot;Returns an instance of self.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_addBeforeGet", "name": "Pouch\\Helpers\\HookManager::addBeforeGet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_addAfterGet", "name": "Pouch\\Helpers\\HookManager::addAfterGet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_addBeforeSet", "name": "Pouch\\Helpers\\HookManager::addBeforeSet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_addAfterSet", "name": "Pouch\\Helpers\\HookManager::addAfterSet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_addBeforeEachGet", "name": "Pouch\\Helpers\\HookManager::addBeforeEachGet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_addAfterEachGet", "name": "Pouch\\Helpers\\HookManager::addAfterEachGet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_addBeforeEachSet", "name": "Pouch\\Helpers\\HookManager::addBeforeEachSet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_addAfterEachSet", "name": "Pouch\\Helpers\\HookManager::addAfterEachSet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_runBeforeGet", "name": "Pouch\\Helpers\\HookManager::runBeforeGet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_runAfterGet", "name": "Pouch\\Helpers\\HookManager::runAfterGet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_runBeforeSet", "name": "Pouch\\Helpers\\HookManager::runBeforeSet", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Helpers\\HookManager", "fromLink": "Pouch/Helpers/HookManager.html", "link": "Pouch/Helpers/HookManager.html#method_runAfterSet", "name": "Pouch\\Helpers\\HookManager::runAfterSet", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Pouch", "fromLink": "Pouch.html", "link": "Pouch/Pouch.html", "name": "Pouch\\Pouch", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_bootstrap", "name": "Pouch\\Pouch::bootstrap", "doc": "&quot;Bootstrap pouch.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_singleton", "name": "Pouch\\Pouch::singleton", "doc": "&quot;Insert or return a singleton instance from our container.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_bind", "name": "Pouch\\Pouch::bind", "doc": "&quot;Bind a new element to the replaceables.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_alias", "name": "Pouch\\Pouch::alias", "doc": "&quot;Create an alias key for an existing key.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_registerNamespaces", "name": "Pouch\\Pouch::registerNamespaces", "doc": "&quot;Register one or more namespaces for automatic resolution.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_get", "name": "Pouch\\Pouch::get", "doc": "&quot;Resolve specific key from the replaceables array.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_raw", "name": "Pouch\\Pouch::raw", "doc": "&quot;Resolve a key without invoking it if it happens to be a factory.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_item", "name": "Pouch\\Pouch::item", "doc": "&quot;Returns the item instance for the key.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_named", "name": "Pouch\\Pouch::named", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_has", "name": "Pouch\\Pouch::has", "doc": "&quot;See if specific key exists in our replaceables.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_remove", "name": "Pouch\\Pouch::remove", "doc": "&quot;Remove key from the container.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_getHookManager", "name": "Pouch\\Pouch::getHookManager", "doc": "&quot;Returns the hook manager.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method___toString", "name": "Pouch\\Pouch::__toString", "doc": "&quot;String representation of a pouch instance.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_count", "name": "Pouch\\Pouch::count", "doc": "&quot;Count elements of the container.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Pouch", "fromLink": "Pouch/Pouch.html", "link": "Pouch/Pouch.html#method_validateData", "name": "Pouch\\Pouch::validateData", "doc": "&quot;Throws an exception if the callable argument is not a callable.&quot;"},
            
            {"type": "Class", "fromName": "Pouch", "fromLink": "Pouch.html", "link": "Pouch/Resolvable.html", "name": "Pouch\\Resolvable", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method___construct", "name": "Pouch\\Resolvable::__construct", "doc": "&quot;From createClassDependency&#039;s inner class&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_make", "name": "Pouch\\Resolvable::make", "doc": "&quot;Set the object for the resolvable. Also resolve constructor dependencies if needed.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_getObject", "name": "Pouch\\Resolvable::getObject", "doc": "&quot;Return the current object of this instance.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_getType", "name": "Pouch\\Resolvable::getType", "doc": "&quot;Get the type of an anything accurately. If it&#039;s an object, the exact class name will be returned.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method___call", "name": "Pouch\\Resolvable::__call", "doc": "&quot;Magic __call method to handle the automatic resolution of parameters.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_resolveDependencies", "name": "Pouch\\Resolvable::resolveDependencies", "doc": "&quot;Resolve the dependencies for all parameters.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_resolveInternalDependencies", "name": "Pouch\\Resolvable::resolveInternalDependencies", "doc": "&quot;Returns params (dependencies) for internal anonymous classes.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_createClassDependency", "name": "Pouch\\Resolvable::createClassDependency", "doc": "&quot;Creates missing class if it can be found in the container. Auto-injecting classes from within\nthe container require them to be prefixed with \\Pouch\\Key.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_getName", "name": "Pouch\\Resolvable::getName", "doc": "&quot;Name getter.&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_getContent", "name": "Pouch\\Resolvable::getContent", "doc": "&quot;From  createClassDependency&#039;s inner class&quot;"},
                    {"type": "Method", "fromName": "Pouch\\Resolvable", "fromLink": "Pouch/Resolvable.html", "link": "Pouch/Resolvable.html#method_isAnonymous", "name": "Pouch\\Resolvable::isAnonymous", "doc": "&quot;Adapter to comply to ReflectionClass.&quot;"},
            
            
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


