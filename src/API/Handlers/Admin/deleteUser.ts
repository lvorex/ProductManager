import { app } from "../../main";
import { functions } from "../../Functions/functions";

export async function deleteUser() {
    console.log("Handlers > deleteUser Ready.")
    app.get("/deleteUser.aras", async (req, res) => {
        const query = req.query
        const userId = query.userId
        
        const injection = functions.injectionCheck(String(userId))
        if (injection == true) {
            res.end("Security problems with used character. Please don't use it.")
            return
        }

        let sql = `SELECT * FROM users WHERE id = '${userId}'`
        let result: any = await functions.arasql.query(sql)
        if (result.length == 0) {
            res.end("User not found.")
            return
        }
        result = result[0]

        if (result.permission == "admin") {
            res.end("Can't delete admin user.")
            return
        }

        sql = `DELETE FROM users WHERE id = '${userId}'`
        result = await functions.arasql.query(sql)
        if (result == false) {
            res.end("Query error.")
            return
        }

        res.end("true")
    })
}