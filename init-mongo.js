db.createUser(
    {
        user : "dba",
        pwd : "dba",
        roles : [
            {
                role : "readWrite",
                db : "dba"
            }
        ]
    }
)