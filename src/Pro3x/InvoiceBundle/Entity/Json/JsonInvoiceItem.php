<?php

namespace Pro3x\InvoiceBundle\Entity\Json;

class JsonInvoiceItem implements \JsonSerializable {
    
    /**
     *
     * @var \Pro3x\InvoiceBundle\Entity\InvoiceItem 
     */
    private $item;
    
    function __construct($item) {
        $this->item = $item;
    }
    
    function format($number) {
        return \number_format($number, 2, ',', '');
    }

    public function jsonSerialize() {
        return array(
            'unitPrice' => $this->format($this->item->getTaxedPrice()),
            'amount' => $this->format($this->item->getAmount()),
            'unit' => $this->item->getUnit(),
            'totalPrice' => $this->format($this->item->getTotalTaxedPrice()),
            'discount' => $this->format($this->item->getDiscount() * 100),
            'discountPrice' => $this->format($this->item->getDicountPrice())
        );
    }
}
