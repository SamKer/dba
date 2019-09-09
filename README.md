# DBA - DATA BASE ARCHIVER

simple tool to archive database

- easy to add plugin
- plugins for dump, compress or upload

## installation

git clone http://omnibus-pic.gendarmerie.fr/gitlab/stc/dba.git

cd dba
cp config.template.yml config.yml
composer install
./dba

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

- Mysql
    - dumper
        - class #\\DBA\\Dumper\Mysql
        - dbhost
        - dbport
        - dbname
        - dbuser
        - dbpassword
- Pgsql
    - dumper
        - class #\\DBA\\Dumpers\Pgsql    
        - dbhost
        - dbport
        - dbname
        - dbuser
        - dbpassword
- Skip
    - dumper
        - class #\\DBA\\Dumpers\Skip
        - localfile 

#### the compressors

- Raw (default)
    - compressor:
        - class: "\\DBA\\Compressors\\Raw"
               
- Zip
    - compressor:
        - class: "\\DBA\\Compressors\\Raw"
#### the archivers
  
- S3
    - archiver:
        - class #\\class\\namespace    
        - endpoint # url s3
        - bucket # bucket name
        - secret # your secret key
        - keyname # your id name
- Dir
    - archiver
        - class #\\class\\namespace    
        - directory # path to put file
        
        
# save cron
crontab -e -u ownerfilesdba 
0 1 * * */path/to/dba/dba bas:archive yourdatabaseconfigname 
        