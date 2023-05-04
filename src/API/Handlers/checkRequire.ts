import { app } from "../main";
import { functions } from "../Functions/functions";

export async function checkRequire() {
    app.get("/checkRequire.aras", async (req, res) => {
        const query = req.query
        const craftingProduct = query.craftingProduct
        
        const injection = functions.injectionCheck(String(craftingProduct))
        if (injection == true) {
            res.end("Security problems with used character. Please don't use it.")
            return
        }

        let sql = `SELECT * FROM \`require\`${craftingProduct ? " WHERE craftingProduct = '"+craftingProduct+"'" : ""}`
        let result: any = await functions.arasql.query(sql)
        if (result.length == 0) {
            res.end("[]")
            return
        }

        const json: {}[] = []

        result.forEach(async (require: any) => {
            json.push({
                id: require.id,
                craftingProduct: require.craftingProduct,
                needProducts: require.needProducts,
                needAmountPerOne: require.needAmountPerOne
            })
        })

        res.end(JSON.stringify(json))
    })
}