export function injectionCheck(insertedProduct: string): boolean {
    if (insertedProduct.includes("'") || insertedProduct.includes('"')) {
        return true
    } else return false
}