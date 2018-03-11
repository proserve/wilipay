#!/bin/bash
echo 'deploy script start...'
set -e

docker build -t eu.gcr.io/${GCP_PROJECT_NAME}/${DOCKER_IMAGE_NAME}:$TRAVIS_COMMIT .

echo $GCLOUD_SERVICE_KEY_STG | base64 --decode -i > ${HOME}/gcloud-service-key.json
gcloud auth activate-service-account --key-file ${HOME}/gcloud-service-key.json

gcloud --quiet config set project $GCP_PROJECT_NAME
gcloud --quiet config set container/cluster $CLUSTER_NAME
gcloud --quiet config set compute/zone ${CLOUDSDK_COMPUTE_ZONE}
gcloud --quiet container clusters get-credentials $CLUSTER_NAME

gcloud docker -- push eu.gcr.io/${GCP_PROJECT_NAME}/${DOCKER_IMAGE_NAME}

yes | gcloud container images add-tag eu.gcr.io/${GCP_PROJECT_NAME}/${DOCKER_IMAGE_NAME}:$TRAVIS_COMMIT eu.gcr.io/${GCP_PROJECT_NAME}/${DOCKER_IMAGE_NAME}:latest

kubectl config view
kubectl config current-context

kubectl set image deployment/${KUBE_DEPLOYMENT_NAME} ${KUBE_DEPLOYMENT_CONTAINER_NAME}=eu.gcr.io/${GCP_PROJECT_NAME}/${DOCKER_IMAGE_NAME}:$TRAVIS_COMMIT
