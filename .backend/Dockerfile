FROM adnanahmady/php:8.2-fpm-alpine

#RUN apk add --no-cache --update icu-dev \
#    && docker-php-ext-configure intl \
#    && docker-php-ext-install intl

RUN echo 'short_open_tag=off' > /usr/local/etc/php/conf.d/docker-php-set-short_open_tag.ini \
    && echo 'post_max_size=400M' > /usr/local/etc/php/conf.d/docker-php-set-post_max_size.ini

USER docker

RUN echo 'alias art="php artisan"' >> $HOME/.bashrc \
    && echo 'function make() { php artisan make:$@; }' >> $HOME/.bashrc \
    && echo 'alias gs="git status"' >> $HOME/.bashrc \
    && echo 'alias gd="git diff"' >> $HOME/.bashrc \
    && echo 'alias g="git"' >> $HOME/.bashrc \
    && echo 'alias ga="git add"' >> $HOME/.bashrc \
    && echo 'alias gl="git log"' >> $HOME/.bashrc \
    && echo 't() { composer test -- $(echo $1 | sed "s/.*test/test/"); }' >> $HOME/.bashrc \
    && echo 'alias tf="composer test -- --filter"' >> $HOME/.bashrc

USER root
