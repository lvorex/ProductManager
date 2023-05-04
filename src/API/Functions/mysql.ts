import { createConnection } from "mysql";
import config from "../config.json";
const server = createConnection({
    host: config.sql.host,
    user: config.sql.user,
    password: config.sql.pass,
    database: config.sql.db
});

async function connect() {
    return new Promise((resolve, reject) => {
        server.connect(() => {
            return resolve(true)
        })
    })
}

async function query(sql: string) {
    return new Promise((resolve, reject) => {
        server.query(sql, (err, results, fields) => {
            if (err) return resolve(false)
            return resolve(results)
        })
    })
}

export const arasql = {
    connect,
    query
}