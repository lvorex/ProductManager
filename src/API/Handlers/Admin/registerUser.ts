import { app } from "../../main";
import { functions } from "../../Functions/functions";
const sha1 = require('sha1');

export async function registerUser() {
    console.log("Handlers > registerUser Ready.")
    app.get("/registerUser.aras", async (req, res) => {
        const query = req.query
        const userId = query.userId
        const userPassword = query.userPassword

        const queries = [userId, userPassword];

        let injection = false
        for (let i = 0; i < queries.length; i++) {
            if (injection == true) continue
            const selectedQuery = queries[i]
            const injectTest = functions.injectionCheck(String(selectedQuery))
            if (injectTest == true) {
                injection = true
            }
        }
        if (injection == true) {
            res.end("Security problems with used character. Please don't use it.")
            return
        }

        if (!userId || !userPassword) {
            res.end("Wrong usage.")
            return
        }

        let sql = `SELECT * FROM users WHERE \`id\` = '${userId}'`
        let result: any = await functions.arasql.query(sql)
        if (result.length > 0) {
            res.end("User already exists.")
            return
        }

        sql = `INSERT INTO users (id, password) VALUES ('${userId}', '${sha1(userPassword)}')`
        result = await functions.arasql.query(sql)
        if (result == false) {
            res.end("Query error.")
            return
        }

        res.end("true")
    })
}