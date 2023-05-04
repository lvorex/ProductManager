import { app } from "../main";
import { functions } from "../Functions/functions";

export async function updateProduct() {
    console.log("Handlers > updateProduct Ready.")
    app.get("/updateProduct.aras", async (req, res) => {
        const query = req.query
        const productId = query.productId
        const toAmount = query.toAmount
        const toStatus = decodeURIComponent(escape(String(query.toStatus)))

        if (!productId) {
            res.end("Wrong usage.")
            return
        }

        const queries = [productId, toAmount, toStatus];

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

        let sql = `SELECT * FROM products WHERE id = ${productId}`
        let result: any = await functions.arasql.query(sql)
        if (result.length == 0) {
            res.end("Product not found.")
            return
        }
        result = result[0]
        const oldProductAmount = result.amount
        const productName = result.name

        const needProducts: any = await functions.checkStock(result.name, Number(toAmount), res)
        if (needProducts == false) {
            res.end("Not enough needed products in stocks.")
            return
        }

        let i = 0
        sql = `UPDATE products SET`
        if (toAmount) {
            sql = sql + ` amount = ${toAmount}`
            i++
        }
        if (toStatus) {
            if (i != 0) {
                sql = sql + `, status = '${toStatus}'`
            } else {
                sql = sql + ` status = '${toStatus}'`
            }
            i++
        }

        if (i == 0) {
            res.end("Wrong usage.")
            return
        }

        sql = sql + ` WHERE id = ${productId}`
        result = await functions.arasql.query(sql)
        if (result == false) {
            res.end("Query error.")
            return
        } else {
            if (!Array.isArray(needProducts)) {
                sql = `SELECT * FROM \`require\` WHERE craftingProduct = '${productName}'`
                result = await functions.arasql.query(sql)
                result = result[0]

                const needAmountPerOne = result.needAmountPerOne

                sql = `SELECT * FROM stock WHERE \`name\` = '${needProducts}'`
                result = await functions.arasql.query(sql)
                if (result.length == 0 || result.length == undefined) {
                    res.end("Stock product not found.")
                    return
                }
    
                const decreaseAmount = (Number(toAmount) - Number(oldProductAmount)) * needAmountPerOne
                // Elimizde 9 soğan stoğu var. Menemen yapmak için 2 soğan'a ihtiyacımız var. 2 Menemen yaparsak elimizde 5 soğan kalır.
    
                result = result[0]
                if (Number(result.amount) - decreaseAmount < 0) {
                    res.end("Can't decrease this amount.")
                    return
                }
        
                sql = `UPDATE stock SET amount = amount - ${decreaseAmount} WHERE id = ${result.id}`
                result = await functions.arasql.query(sql)
                if (result == false) {
                    res.end("Query error.")
                    return
                }
            } else {
                let failed: any
                let i = 0
                for await (const needProduct of needProducts) {
                    sql = `SELECT * FROM \`require\` WHERE craftingProduct = '${productName}'`
                    result = await functions.arasql.query(sql)
                    result = result[0]
    
                    let needAmountPerOne = result.needAmountPerOne
                    if (needAmountPerOne.includes(",")) {
                        needAmountPerOne = needAmountPerOne.split(",")[i]
                    }

                    sql = `SELECT * FROM stock WHERE \`name\` = '${needProduct}'`
                    result = await functions.arasql.query(sql)
                    if (result.length == 0 || result.length == undefined) {
                        res.end("Stock product not found.")
                        failed = true
                        return
                    }
        
                    const decreaseAmount = (Number(toAmount) - Number(oldProductAmount)) * needAmountPerOne
                    // Elimizde 9 soğan stoğu var. Menemen yapmak için 2 soğan'a ihtiyacımız var. 2 Menemen yaparsak elimizde 5 soğan kalır.
        
                    result = result[0]
                    if (Number(result.amount) - decreaseAmount < 0) {
                        res.end("Can't decrease this amount.")
                        failed = true
                        return
                    }
            
                    sql = `UPDATE stock SET amount = amount - ${decreaseAmount} WHERE id = ${result.id}`
                    result = await functions.arasql.query(sql)
                    if (result == false) {
                        res.end("Query error.")
                        failed = true
                        return
                    }
                    i++
                }
                if (failed == true) return
            }
        }


        res.end("true")
    })
}