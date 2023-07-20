# Models

Models are the way that eloquent talks with the database.

## QA

You might ask why I implemented the getter and setters manually while
the laravel is handling it by magic methods?

Will if you use the magic properties everywhere you want to set
or receive the fields data, eventually, you will end up having
multiple places in your project that are using the fields and
when you need to make a change in the system for example changing
the "amount" field name to "count" you will end up changing many
places in your code, and if you say that the IDE will fix this, as
a best practice you should never fully put your trust int tools
that you are using, after all their just some tools and you can
face serious issues just because of some update on the tool.
