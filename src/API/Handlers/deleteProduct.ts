import { app } from "../main";
import { functions } from "../Functions/functions";

export async function deleteProduct() {
    console.log("Handlers > deleteProduct Ready.")
    app.get("/deleteProduct.aras", async (req, res) => {
        const query = req.query
        const productId = query.productId

        const injection = functions.injectionCheck(String(productId))
        if (injection == true) {
            res.end("Security problems with used character. Please don't use it.")
            return
        }

        if (!productId) {
            res.end("Wrong usage.")
            return
        }

        let sql = `SELECT * FROM products WHERE id = ${productId}`
        let result: any = await functions.arasql.query(sql)
        if (result.length == 0) {
            res.end("Product not found.")
            return
        }

        sql = `DELETE products WHERE id = ${productId}`
        result = await functions.arasql.query(sql)
        if (result == false) {
            res.end("Query error.")
            return
        }

        res.end(true)
    })
}