# Leon
### A simple yet sophisticated extensible PHP/MySQL framework for Applications

#### Standards & Conventions:
* PSR-4
* Use Full Names for classes, properties and variables whenever possible (e.g. 'Configuration' vs. 'Config')
* Use camelCase for variables and properties, capitalizing all acronyms (e.g 'camelCase', 'HTML')
* The word 'Class' should *only* be used in reference to a fully-qualified class name (FQCN)

#### Notes on v1:
* v1 incorporates and builds upon work that we've done and seen in custom applications over the last decade
* In particular, we are inspired by the routing and form-management capabilities of Symfony, and seek to incorporate them (among others) into a light-weight package which gives developers an effective toolset then gets out of your way
* Note that we take a broad perspective on the concept of application configuration; Our Configuration classes gather and provide information about available routes and the current request. Extend the Configuration class to incorporate additional properties and methods from your configuration.yml file, then 'global $configuration;' to use it (We are looking to incorporate DI for v2; see below).
* Likewise anywhere you need access to the database connection just 'global $db;'. As above, we are looking into using DI for v2, but at present it is all-too-convenient to get $config and $db out of the global scope, and we've kept the architecture simple enough to not demand DI just yet

##### Goals for v1:
* @ToDo add API component to allow OAuth2 connections
* @ToDo improve Routing components to allow more flexibility with respect to parameter placements (i.e. allowing more proper RESTfullness)
* @ToDo add Console component to provide CLI access to the application (especially for crons)
* @ToDo add DataManager component to treat data as Objects and manage their DB records automatically

##### v2 @ToDo
* Implement PSR-4 more thoroughly
* use Dependency Injection
