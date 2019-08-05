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
    - params
        - class #\\class\\namespace
        - dbhost
        - dbport
        - dbname
        - dbuser
        - dbpassword
- Pgsql
    - params
        - class #\\class\\namespace    
        - dbhost
        - dbport
        - dbname
        - dbuser
        - dbpassword

#### the compressors

- Raw (default)
- Zip

#### the archivers
  
- S3
    - params
        - class #\\class\\namespace    
        - endpoint # url s3
        - bucket # bucket name
        - secret # your secret key
        - keyname # your id name
- Dir
    - params
        - class #\\class\\namespace    
        - directory # path to put file