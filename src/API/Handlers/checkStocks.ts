import { app } from "../main";
import { functions } from "../Functions/functions";

export async function checkStocks() {
    console.log("Handlers > checkStocks Ready.")
    app.get("/checkStocks.aras", async (req, res) => {
        const query = req.query
        const productName = query.productName

        const injection = functions.injectionCheck(String(productName))
        if (injection == true) {
            res.end("Security problems with used character. Please don't use it.")
            return
        }

        let sql = `SELECT * FROM stock${productName ? " WHERE `name` = '"+productName+"'" : ""}`
        let result: any = await functions.arasql.query(sql)
        if (result.length == 0) {
            res.end("[]")
            return
        }
            
        const json: {}[] = []

        result.forEach(async (product: any) => {
            json.push({
                id: product.id,
                name: product.name,
                amount: product.amount
            })
        })

        res.end(JSON.stringify(json))
        return
    })
}