import { app } from "../../main";
import { functions } from "../../Functions/functions";

export async function showUsers() {
    console.log("Handlers > showUsers Ready.")
    app.get("/showUsers.aras", async (req, res) => {
        const query = req.query
        const userId = query.userId

        const injection = functions.injectionCheck(String(userId))
        if (injection == true) {
            res.end("Security problems with used character. Please don't use it.")
            return
        }

        let sql = `SELECT * FROM users${userId ? " WHERE `id` = '"+userId+"'" : ""}`
        let result: any = await functions.arasql.query(sql)
        if (result.length == 0) {
            res.end("[]")
            return
        }
        
        const json: {}[] = []

        result.forEach(async (user: any) => {
            json.push({
                id: user.id,
                permission: user.permission
            })
        })

        res.end(JSON.stringify(json))
    })
}