# DBA - DATA BASE ARCHIVER

simple tool to archive database

- easy to add plugin
- plugins for dump, compress or upload

## installation

### depuis git
```bash
git clone http://omnibus-pic.gendarmerie.fr/gitlab/stc/dba.git
cd dba
cp config.template.yml config.yml
composer install
./dba
```

### depuis nexus
```bash
curl http://nexus-pic.gendarmerie.fr/repository/binaries-stc/dba/dba-1.0.1.zip
unzip dba-1.0.1.zip
cd dba
./dba
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
0 1 * * */path/to/dba/dba bas:archive yourdatabaseconfigname
```

        
        
