# wilipay
[![Build Status](https://travis-ci.com/wilipay/wilipay.svg?token=gre7NQjoinSkmrrbkqMV&branch=master)](https://travis-ci.com/wilipay/wilipay)
1. [How to setup kubernetes cluster to connect to Google clous SQL](https://cloud.google.com/sql/docs/postgres/connect-kubernetes-engine)
2. [how to run minikube with local containers](https://stackoverflow.com/questions/42564058/how-to-use-local-docker-images-in-kubernetes/42564211)
3. how to authorise minikube to pull images from Google Cloud Container Registry
```bash
kubectl  create secret docker-registry gcr-json-key \
          --docker-server=https://gcr.io \
          --docker-username=_json_key \
          --docker-password="$(cat ~/Documents/beetfree/service_accounts/gcr-test.json)" \
          --docker-email=khalid.ghiboub@gmail.com
```

```bash 
kubectl patch serviceaccount default -p '{"imagePullSecrets": [{"name": "gcr-json-key"}]}'
```
4. [Travis-ci integration tutorial](https://medium.com/google-cloud/continuous-delivery-in-a-microservice-infrastructure-with-google-container-engine-docker-and-fb9772e81da7)

#### Fix homestead clock sync problem
```bash 
sudo service ntp stop
sudo ntpd -gq
sudo service ntp start
```
required
```bash 
php artisan passport:install
```
