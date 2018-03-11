#!/usr/bin/env bash
# list images
curl http://localhost:5000/v2/_catalog
# list tags
curl http://localhost:5000/v2/wilipay/tags/list
# start minikube to work with local images
minikube start --insecure-registry=localhost:5000

# how to run minikube localy without
# https://stackoverflow.com/questions/42564058/how-to-use-local-docker-images-in-kubernetes/42564211
eval $(minikube docker-env)
# for kubernetes yaml file imagePullPolicy: IfNotPresent

kubectl  create secret docker-registry gcr-json-key \
          --docker-server=https://gcr.io \
          --docker-username=_json_key \
          --docker-password="$(cat ~/Downloads/gcr-test.json)" \
          --docker-email=khalid.ghiboub@gmail.com

kubectl patch serviceaccount default -p '{"imagePullSecrets": [{"name": "gcr-json-key"}]}'

./cloud_sql_proxy -instances=beetfree-193913:europe-west1:wilipay-cloud-sql=tcp:5431
