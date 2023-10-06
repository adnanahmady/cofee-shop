FROM node:18-alpine3.17

RUN deluser --remove-home node \
    && apk add --no-cache --update sudo \
    && adduser -D "docker" docker \
    && echo "docker ALL=(ALL) NOPASSWD: ALL" > /etc/sudoers.d/docker \
    && chmod 0400 /etc/sudoers.d/docker \
    && chmod 777 /tmp
