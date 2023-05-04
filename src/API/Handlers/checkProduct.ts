import { app } from "../main";
import { functions } from "../Functions/functions";

export async function checkProduct() {
    console.log("Handlers > checkProduct Ready.")
    app.get("/checkProduct.aras", async (req, res) => {
        const query = req.query
        const id = query.productId
        const name = query.productName
        const amount = query.productAmount
        const status = decodeURIComponent(escape(String(query.productStatus)))

        const queries = [id, name, amount, status];

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
        
        let sql = ""
        
        if (id) {
            sql = `SELECT * FROM products WHERE id = '${id}'`
        } else if (name) {
            sql = `SELECT * FROM products WHERE \`name\` = '${name}'`
        } else if (!amount && status == "undefined") {
            sql = "SELECT * FROM products"
            console.log(sql)
        } else {
            let i = 0
            sql = `SELECT * FROM products WHERE`
            if (amount != "") {
                i++
                sql = sql + ` amount = ${amount}`                
            }
            if (status != "") {
                if (i != 0) {
                    sql = sql + ` AND status = '${status}'`
                } else {
                    sql = sql + ` status = '${status}'`
                }
            }
        }

        console.log(sql)

        let result: any = await functions.arasql.query(sql)
        if (result.length == 0 || result.length == undefined) {
            res.end("[]")
            return
        }

        const json: {
            id: number,
            name: string,
            amount: number,
            status: string
        }[] = []

        result.forEach(async (product: any) => {
            json.push({
                id: product.id,
                name: product.name,
                amount: product.amount,
                status: product.status
            })
        })

        res.end(JSON.stringify(json))
    })
}