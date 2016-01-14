# Simple Web Framework
Rudimentary framework for easy building of simple web applications

### Simple Web Framework/core
This package consists of core parts of the framework. 

## Contents

* [Overview](#overview)
* [The URL](#the-url)
* [Files](#files)
* [Routing](#routing)
* [Plug-ins](#plug-ins)

<h2 id="overview">Overview</h2>

This is small library that serves as a foundation to build simple web applications. It provides very rudimentary split of control/action/presentation. Apart from that its scope is very basic.  

It does not provide neither model, mechanism for accessing data nor any presentation layer. 

All it does it provides mechanism for URL analysis, some very simple routing and possibility to expand it with system of plug-ins.

<h2 id="the-url">The URL</h2>
Simple Web Framework expects URL to be: 

	/pagename/param1/val1/param2/val2/name.ext

where:
* pagename - is name of the directory where  
* param1/param2  - are parameters available to the application. Values they hold are corresponding val1/val2.  
* name.ext - serves primarily decorative purpose(but still is available to use)

Example:

	http://www.example.org/documentedit/id/425/introduction.html

<h2 id="files">Files</h2>

Based on that application searches in the 'pages' directory for a subdirectory named the same as 'pagename' ('documentedit' in the example). There it expects to find five PHP files:

- validator.php
- formprep.php
- form.php  
- action.php
- view.php  

<h2 id="routing">Routing</h2>
The logic is as follows:

- validator.php - decides if we need to show form (or do we need to show it with an error message). 
If there is a error in received form data (e.g. some required fields are empty) or there is no data in $_POST - two files are included:  

  - formprep.php - prepares data for display in form (eg reads data from db)
  - form.php     - displays form

  if there is no error or there is no validator  whatsoever (and subsequently no form.php and no formprep.php) framework will skip to

 - action.php - performs an action( eg reads from db, saves to db )
 - response.php - outputs data to client or redirects to another page

This is all the framework does. Everything else has to be done either in those files or through plug-in system.

<h2 id="plug-ins">Plug-ins</h2>
Plug-ins are the primary way to extend functionality of the framework

During execution Simple Web Framework may emit any of those events:     

- start
- validator
- formprep
- form  
- action
- view
- finish
and 
- error 

Framework allows plug-ins to make use of it through hooks: 
 - before{event}
 - on{event}
 - after{event}

To make use of the hook plug-in needs to have method of that name (e.g..  beforeStart or afterAction)    


