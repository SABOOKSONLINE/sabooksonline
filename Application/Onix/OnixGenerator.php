<?php
class OnixGenerator {
    public static function generate($books) {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><ONIXMessage></ONIXMessage>');


// Add ONIX header
$header = $xml->addChild('Header');
$sender = $header->addChild('Sender');
$sender->addChild('SenderName', 'sabooksonline');
$sender->addChild('EmailAddress', 'support@sabooksonline.co.za');
$header->addChild('SentDateTime', date('YmdHis'));

foreach ($books as $book) {
    $product = $xml->addChild('Product');

    // --- Record Reference & Notification Type ---
    $product->addChild('RecordReference', htmlspecialchars($book['ID'], ENT_XML1, 'UTF-8'));
    $product->addChild('NotificationType', '03');

    // --- Product Identifier ---
    $productIdentifier = $product->addChild('ProductIdentifier');
    $productIdentifier->addChild('ProductIDType', '15'); // 15 = ISBN-13
    $productIdentifier->addChild('IDValue', htmlspecialchars($book['ISBN'], ENT_XML1, 'UTF-8'));

    // --- Descriptive Detail ---
    $descriptiveDetail = $product->addChild('DescriptiveDetail');
    $descriptiveDetail->addChild('ProductComposition', '00'); // Single item
    $productForm = !empty($book['PDFURL']) ? 'EA' : 'BA';
    $descriptiveDetail->addChild('ProductForm', htmlspecialchars($productForm, ENT_XML1, 'UTF-8'));

    // Title
    $titleDetail = $descriptiveDetail->addChild('TitleDetail');
    $titleDetail->addChild('TitleType', '01'); // Distinctive title
    $titleElement = $titleDetail->addChild('TitleElement');
    $titleElement->addChild('TitleElementLevel', '01');
    $titleElement->addChild('TitleText', htmlspecialchars($book['TITLE'], ENT_XML1, 'UTF-8'));

    // Contributor
    $contributor = $descriptiveDetail->addChild('Contributor');
    $contributor->addChild('ContributorRole', 'A01'); // Author
    $contributor->addChild('PersonName', htmlspecialchars($book['AUTHORS'], ENT_XML1, 'UTF-8'));

    // Language
    $language = $descriptiveDetail->addChild('Language');
    $language->addChild('LanguageRole', '01');
    $productForm = !empty($book['LANGUAGES']) ? 'eng' : 'eng';
    $language->addChild('LanguageCode', htmlspecialchars($productForm, ENT_XML1, 'UTF-8'));

    // --- Collateral Detail (Cover image) ---
    if (!empty($book['COVER'])) {
        $collateralDetail = $product->addChild('CollateralDetail');
        $supportingResource = $collateralDetail->addChild('SupportingResource');
        $supportingResource->addChild('ResourceContentType', '01'); // Front cover
        $supportingResource->addChild('ContentAudience', '00'); // Unrestricted
        $supportingResource->addChild('ResourceMode', '03'); // Image

        $coverUrl = 'https://sabooksonline.co.za/cms-data/book-covers/' . urlencode($book['COVER']);

        $resourceVersion = $supportingResource->addChild('ResourceVersion');
        $resourceVersion->addChild('ResourceForm', '02'); // Downloadable file
        $resourceVersion->addChild('ResourceLink', htmlspecialchars($coverUrl, ENT_XML1, 'UTF-8'));

    }

    // --- Publishing Detail ---
    $publishingDetail = $product->addChild('PublishingDetail');
    $publisher = $publishingDetail->addChild('Publisher');
    $publisher->addChild('PublishingRole', '01'); // Publisher
    $publisher->addChild('PublisherName', htmlspecialchars($book['PUBLISHER'], ENT_XML1, 'UTF-8'));

    $publishingDate = $publishingDetail->addChild('PublishingDate');
    $publishingDate->addChild('PublishingDateRole', '01'); // Publication date
    $publishingDate->addChild('Date', htmlspecialchars($book['DATEPOSTED'], ENT_XML1, 'UTF-8'));

    // --- Product Supply ---
    $productSupply = $product->addChild('ProductSupply');
    $supplyDetail = $productSupply->addChild('SupplyDetail');

    // Supplier
    $supplier = $supplyDetail->addChild('Supplier');
    $supplier->addChild('SupplierRole', '01'); // Publisher
    $supplier->addChild('SupplierName', 'sabooksonline');

    // Availability
    $supplyDetail->addChild('ProductAvailability', '20'); // Available

    // Price
    $price = $supplyDetail->addChild('Price');
    $price->addChild('PriceType', '01'); // RRP excluding tax
    $price->addChild('PriceAmount', htmlspecialchars($book['RETAILPRICE'], ENT_XML1, 'UTF-8'));
    $price->addChild('CurrencyCode', 'ZAR'); // South African Rand
}

echo $xml->asXML();
    }
}
