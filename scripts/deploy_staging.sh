#!/usr/bin/env bash
IMAGE='eu.gcr.io/beetfree-193913/wilipay:v0.1'
gcloud container clusters get-credentials wilipay-staging --zone europe-west1-b --project beetfree-193913
echo 'custom_docker build -t gcr.io/beetfree-193913/$IMAGE .'
docker build -t $IMAGE .
gcloud docker -- push $IMAGE
