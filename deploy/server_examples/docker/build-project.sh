#!/bin/bash

DOCKER_ID='freematiq'
PROJECT_REPO='git@gitlab.freematiq.com:quartz/yii2-base-app.git'
PROJECT_BRANCH='master'
PROJECT_DIR='/opt/baseapp/project'
SSH_KEYS='/home/users/.ssh'
GITLAB_HOST='gitlab.freematiq.com:46.4.155.93'

docker run -it --rm -v="$SSH_KEYS:/root/.ssh" -v="$PROJECT_DIR:/opt/project" --add-host=$GITLAB_HOST $DOCKER_ID/git git clone $PROJECT_REPO /opt/project
docker run -it --rm -v="$SSH_KEYS:/root/.ssh" -v="$PROJECT_DIR:/opt/project" --add-host=$GITLAB_HOST -w /opt/project $DOCKER_ID/git git checkout $PROJECT_BRANCH
docker run -it --rm -v="$SSH_KEYS:/root/.ssh" -v="$PROJECT_DIR:/opt/project" --add-host=$GITLAB_HOST -w /opt/project $DOCKER_ID/git git pull origin $PROJECT_BRANCH

docker-compose -f project/deploy/docker/docker-compose.yml -f docker-ssh.yml run --rm -w /opt/project/deploy php make build-prod
docker-compose -f project/deploy/docker/docker-compose.yml -f docker.yml up -d