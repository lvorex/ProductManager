import { app } from "../main";
import { functions } from "../Functions/functions";
const sha1 = require("sha1");

export async function loginUser() {
    console.log("Handlers > loginUser Ready.")
    app.get("/loginUser.aras", async (req, res) => {
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

        let sql = `SELECT * FROM users WHERE id = '${userId}' AND password = '${sha1(userPassword)}'`
        let result: any = await functions.arasql.query(sql)
        if (result.length == 0) {
            res.end("Wrong User ID or Password.")
            return
        }
        result = result[0]

        res.end(result.permission)
    })
}