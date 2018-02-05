#!/usr/bin/env bash
IMAGE='eu.gcr.io/beetfree-193913/beetfree-frontend:v0.1'
gcloud container clusters get-credentials beetfree-staging --zone europe-west1-b --project beetfree-193913
echo 'docker build -t gcr.io/beetfree-193913/$IMAGE .'
docker build -t $IMAGE .
gcloud docker -- push $IMAGE
kubectl set image deployment/nginx-deployment beetfree-frontend=$IMAGE