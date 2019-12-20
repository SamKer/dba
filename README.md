# DBA - DATA BASE ARCHIVER

simple tool to archive database

- easy to add plugin
- plugins for dump, compress or upload

## installation
<<<<<<< HEAD
### via git
=======

### depuis git
>>>>>>> 235fc53d658de9a9e4c83b04a0eae6b6799d71d9
```bash
git clone http://omnibus-pic.gendarmerie.fr/gitlab/stc/dba.git
cd dba
cp config.template.yml config.yml
composer install
./dba
```
<<<<<<< HEAD
### via nexus
curl http://registry-pic.gendarmerie.fr/repository/binaries-stc/dba/dba-1.0.1.zip
=======

### depuis nexus
```bash
curl http://nexus-pic.gendarmerie.fr/repository/binaries-stc/dba/dba-1.0.1.zip
unzip dba-1.0.1.zip
cd dba
./dba
```

>>>>>>> 235fc53d658de9a9e4c83b04a0eae6b6799d71d9

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
- Dir
    - archiver
        - class #\\class\\namespace    
        - directory # path to put file
<<<<<<< HEAD
        
      
# tests
cd /pat/to/dba
      
        
# save cron
```bash
crontab -e -u ownerfilesdba
```

```crontab 
0 1 * * */path/to/dba/dba bas:archive yourdatabaseconfigname
```

``` 
=======
```       
        
# save cron
```yml
crontab -e -u ownerfilesdba 
```

```crontab
0 1 * * */path/to/dba/dba bas:archive yourdatabaseconfigname
```
>>>>>>> 235fc53d658de9a9e4c83b04a0eae6b6799d71d9
        