# DBA - DATA BASE ARCHIVER

simple tool to archive database

- easy to add plugin
- plugins for dump, compress or upload

## installation

dépendences
```bash
sudo apt install curl php7.2-cli php7.2-zip
```

pour l'archivage sur scality
add caroot scality if error
```bash
curl https://scality-s3.gendarmerie.fr
```
if error ssl
```bash
echo quit | openssl s_client -showcerts -servername scality-s3.gendarmerie.fr -connect scality-s3.gendarmerie.fr:443 > ScalityCa.crt
curl https://scality-s3.gendarmerie.fr --cacert ScalityCa.crt
```
si ok alors on le pose dans la ca global list
```bash
cp ScalityCa.crt /usr/share/ca-certificates/
echo "ScalityCa.crt" >> /etc/ca-certificates.conf
update-ca-certificates
curl https://scality-s3.gendarmerie.fr
```
si plus d'erreur ssl alors ok


### source from git
```bash
git clone https://github.com/samker/dba.git
cd dba
cp config.template.yml config.yml
composer install
./dba
```

### source from nexus
```bash
sudo su
cd /var/www/
curl http://nexus-pic.gendarmerie.fr/repository/binaries-stc/dba/dba-1.2.1.tar.gz -o dba-1.2.1.tar.gz
tar -xvzf dba-1.2.1.tar.gz
rm dba-1.2.1.tar.gz
cd dba/
```


### define config

#### database target

each config need at least 2 plugins (1 dumper (from), 1 archiver (to))

```yml
targets:
    your_config_name:
        dumper:
            params...
        compressor:
            params...
        archiver:
            params...
        
```

#### the dumpers
```yml
- Mysql
    - dumper
        - class #\\DBA\\Dumper\Mysql
        - dbhost: #localhost
        - dbport: #3306
        - dbname: #database name
        - dbuser: #database user
        - dbpassword: #database password
- Pgsql
    - dumper
        - class: #\\DBA\\Dumpers\Pgsql    
        - dbhost: # localhost
        - dbport: #5432
        - dbname: #database name
        - dbuser: #database user
        - dbpassword: #database password
- Mongo
    - dumper
        - class: #\\DBA\\Dumpers\Mongo    
        - dbhost: # localhost
        - dbport: #5432
        - dbname: #database name
        - dbuser: #database user
        - dbauthentication: # db user authentication
        - dbpassword: #database password
- Skip
    - dumper
        - class #\\DBA\\Dumpers\Skip
        - localfile: /path/to/local/file 
```
#### the compressors
```yml
- Raw (default)
    - compressor:
        - class: "\\DBA\\Compressors\\Raw"
               
- Zip
    - compressor:
        - class: "\\DBA\\Compressors\\Raw"
```

#### the archivers
  ```yml
- S3
    - archiver:
        - class #\\class\\namespace    
        - endpoint # url s3
        - bucket # bucket name
        - secret # your secret key
        - keyname # your id name
	    - nlast # maximum entry saved, older is deleted
- Dir
    - archiver
        - class #\\class\\namespace    
        - directory # path to put file
	    - nlast: #maximum entry saved, older is deleted
```        
      
        
# save cron
```bash
crontab -e -u ownerfilesdba
```

```crontab 
0 1 * * */path/to/dba/dba base:archive yourdatabaseconfigname
```

        
        
