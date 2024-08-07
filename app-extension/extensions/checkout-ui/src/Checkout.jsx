import {
  reactExtension,
  Checkbox,
  useApplyCartLinesChange,
  useCartLines,
  useAppMetafields,

} from '@shopify/ui-extensions-react/checkout';

//import use effect, use state, use context, use reducer
import React, { useEffect, useState } from 'react';

// 1. Choose an extension target
export default reactExtension(
  'purchase.checkout.cart-line-list.render-after',
  () => <Extension />,
);

function Extension() {
  const applyCartLinesChange = useApplyCartLinesChange();
  const cartLines = useCartLines();
  const [protectionProduct, setProtectionProduct] = useState(null);
  const [protectionCost, setProtectionCost] = useState(0);
  const [isFirstRender, setIsFirstRender] = useState(true);
  const [isChecked, setIsChecked] = useState(false);
  const [product_id, setProduct_id] = useState(null);
  const [product_admin_id, setProduct_admin_id] = useState(null);
  const [appUrl, setAppUrl] = useState(null);
  const [isRegular, setIsRegular] = useState(true);
  const [firstLoad, setFirstLoad] = useState(true);

  const metafields = useAppMetafields();
  // console.log("metafileds: ",metafields)

  useEffect(() => {
    if (!isFirstRender) {
      return;
    }
    if (metafields) {
      metafields?.forEach((metafieldSet) => {
        let metafield = metafieldSet.metafield;
        if (metafield.key === 'product') {
          let value = JSON.parse(metafield.value);
          setProduct_id(value.product_id);
          setProduct_admin_id(`gid://shopify/Product/${value.product_id}`);
        }
        if (metafield.key === 'app_url') {
          setAppUrl(metafield.value);
        }
      })
    }
  }, [metafields])


  useEffect(() => {
    const lineWithProduct = cartLines.find((line) => line.merchandise?.product?.id === product_admin_id);
    if (lineWithProduct) {
      setProtectionProduct(lineWithProduct);
      setProtectionCost(lineWithProduct.cost.totalAmount.amount);
      console.log('lineWithProduct', lineWithProduct);

      if (isRegular) {
        if (lineWithProduct.attributes?.find((attr) => attr.key === 'checkout_type')) {
          setIsChecked(true);
        } else {
          setIsChecked(false);
          removeCartLine(lineWithProduct);
        }
        setIsRegular(false);
      }
    }
  }, [cartLines, product_id]);

  useEffect(() => {
    if (isFirstRender) return;

    try {
      if (isChecked) {
        addCartLine(protectionProduct)
      } else {
        removeCartLine(protectionProduct);
      }
      setIsFirstRender(true);
    } catch (error) {
      console.error(`Error ${isChecked ? 'adding' : 'removing'} line item:`, error);
    }
  }, [isChecked]);


  const removeCartLine = (lineItem) => {
    applyCartLinesChange({
      type: 'removeCartLine',
      id: lineItem.id,
      quantity: lineItem.quantity
    });
  }

  const addCartLine = (lineItem) => {
    applyCartLinesChange({
      type: 'addCartLine',
      merchandiseId: lineItem.merchandise.id,
      quantity: 1
    });
  }


  // 2. Render a UI
  return (
    <>
      {protectionProduct && (
        <Checkbox
          checked={isChecked}
          onChange={() => {
            setIsFirstRender(false);
            setIsChecked(!isChecked);
          }}
        >
          {isChecked ? 'Added' : 'Add'} Order Protection - {protectionProduct.cost.totalAmount.currencyCode} {protectionCost}
        </Checkbox>
      )}
    </>
  );
}