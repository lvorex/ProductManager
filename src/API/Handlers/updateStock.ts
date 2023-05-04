import { app } from "../main";
import { functions } from "../Functions/functions";

export async function updateStock() {
    console.log("Handlers > updateStock Ready.")
    app.get("/updateStock.aras", async (req, res) => {
        const query = req.query
        const productName = query.productName
        const toAmount = query.toAmount

        if (!productName || !toAmount) {
            res.end("Wrong usage.")
            return
        }

        const queries = [productName, toAmount]

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

        let sql = `SELECT * FROM stock WHERE \`name\` = '${productName}'`
        let result: any = await functions.arasql.query(sql)
        if (result.length == 0 || result.length == undefined) {
            res.end("Stock product not found.")
            return
        }
        result = result[0]

        if (Number(toAmount) < 0) {
            res.end("Amount need to be bigger than 0.")
            return
        }

        sql = `UPDATE stock SET \`amount\` = ${toAmount} WHERE \`name\` = '${productName}'`
        result = await functions.arasql.query(sql)

        if (result == false) {
            res.end("Query error.")
            return
        }

        res.end("true")
    })
}