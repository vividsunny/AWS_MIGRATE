<FatturaElettronicaHeader>
    <DatiTrasmissione>
        <IdTrasmittente>
            <IdPaese><?php echo $invoice_details['transmitter']['country_id']; ?></IdPaese>
            <IdCodice><?php echo $invoice_details['transmitter']['ssn']; ?></IdCodice>
        </IdTrasmittente>
        <ProgressivoInvio><?php echo $invoice_details['formatted_number']; ?></ProgressivoInvio>
        <FormatoTrasmissione><?php echo $invoice_details['transmission_format']; ?></FormatoTrasmissione>
        <CodiceDestinatario><?php echo $invoice_details['customer']['billing_id']; ?></CodiceDestinatario>
        <ContattiTrasmittente>
            <Telefono><?php echo $invoice_details['transmitter']['phone']; ?></Telefono>
            <Email><?php echo $invoice_details['transmitter']['email']; ?></Email>
        </ContattiTrasmittente>
        <?php if( ! $invoice_details['customer']['is_private'] && $invoice_details['customer']['billing_pec'] != '' ) : ?>
            <PECDestinatario><?php echo $invoice_details['customer']['billing_pec']?></PECDestinatario>
        <?php endif; ?>
    </DatiTrasmissione>
    <CedentePrestatore>
        <DatiAnagrafici>
            <IdFiscaleIVA>
                <IdPaese><?php echo $invoice_details['transmitter']['country_id']; ?></IdPaese>
                <IdCodice><?php echo $invoice_details['transmitter']['vat']; ?></IdCodice>
            </IdFiscaleIVA>
            <CodiceFiscale><?php echo $invoice_details['transmitter']['ssn']; ?></CodiceFiscale>
            <Anagrafica>
                <Denominazione><?php echo $invoice_details['transmitter']['registered_name']; ?></Denominazione>
                <?php if( $invoice_details['transmitter']['registered_name'] == '' ): ?>
                    <Nome><?php echo $invoice_details['transmitter']['name']; ?></Nome>
                    <Cognome><?php echo $invoice_details['transmitter']['lastname']; ?></Cognome>
                <?php endif; ?>
            </Anagrafica>
            <RegimeFiscale><?php echo $invoice_details['transmitter']['fiscal_regime']; ?></RegimeFiscale>
        </DatiAnagrafici>
        <Sede>
            <Indirizzo><?php echo $invoice_details['transmitter']['address']; ?></Indirizzo>
            <CAP><?php echo $invoice_details['transmitter']['cap']; ?></CAP>
            <Comune><?php echo $invoice_details['transmitter']['city']; ?></Comune>
            <Provincia><?php echo $invoice_details['transmitter']['province']; ?></Provincia>
            <Nazione>IT</Nazione>
        </Sede>
    </CedentePrestatore>
    <CessionarioCommittente>
        <DatiAnagrafici>
            <?php if( $invoice_details['customer']['billing_company'] != '' ): ?>
                <IdFiscaleIVA>
                    <IdPaese><?php echo $invoice_details['customer']['billing_country'] ?></IdPaese>
                    <IdCodice><?php echo $invoice_details['customer']['billing_vat_number'] ?></IdCodice>
                </IdFiscaleIVA>
            <?php endif; ?>
            <?php if( $invoice_details['customer']['billing_ssn'] != '' ): ?>
                <CodiceFiscale><?php echo strtoupper($invoice_details['customer']['billing_ssn']) ?></CodiceFiscale>
            <?php endif; ?>
            <Anagrafica>
                <?php if( $invoice_details['customer']['billing_company'] != '' ): ?>
                    <Denominazione><?php echo $invoice_details['customer']['billing_company']; ?></Denominazione>
                <?php else: ?>
                    <Nome><?php echo $invoice_details['customer']['billing_first_name']; ?></Nome>
                    <Cognome><?php echo $invoice_details['customer']['billing_last_name']; ?></Cognome>
                <?php endif; ?>
            </Anagrafica>

        </DatiAnagrafici>
        <Sede>
            <Indirizzo><?php echo $invoice_details['customer']['billing_address_1']; ?> </Indirizzo>
            <CAP><?php echo $invoice_details['customer']['billing_postcode']; ?></CAP>
            <Comune><?php echo $invoice_details['customer']['billing_city']; ?></Comune>
            <?php if( $invoice_details['customer']['billing_country'] == 'IT' ): ?>
                <Provincia><?php echo $invoice_details['customer']['billing_state']; ?></Provincia>
            <?php endif; ?>
            <Nazione><?php echo $invoice_details['customer']['billing_country']; ?></Nazione>
        </Sede>
    </CessionarioCommittente>
    <?php if( $invoice_details['third_intermediary']['enable'] == 'yes' ): ?>
        <TerzoIntermediarioOSoggettoEmittente>
            <DatiAnagrafici>
                <IdFiscaleIVA>
                    <IdPaese><?php echo $invoice_details['third_intermediary']['country'] ?></IdPaese>
                    <IdCodice><?php echo $invoice_details['third_intermediary']['vat'] ?></IdCodice>
                </IdFiscaleIVA>
                <CodiceFiscale><?php echo $invoice_details['third_intermediary']['ssn'] ?></CodiceFiscale>
                <Anagrafica>
                    <?php if( !empty($invoice_details['third_intermediary']['registered_name']) ): ?>
                        <Denominazione><?php echo $invoice_details['third_intermediary']['registered_name'] ?></Denominazione>
                    <?php else: ?>
                        <Nome><?php echo $invoice_details['third_intermediary']['name'] ?></Nome>
                        <Cognome><?php echo $invoice_details['third_intermediary']['lastname'] ?></Cognome>
                    <?php endif; ?>

                    <?php if( !empty($invoice_details['third_intermediary']['qualification']) ): ?>
                        <Titolo><?php echo $invoice_details['third_intermediary']['qualification'] ?></Titolo>
                    <?php endif; ?>

                    <?php if( !empty( $invoice_details['third_intermediary']['codeori'] ) ): ?>
                        <CodEORI><?php echo $invoice_details['third_intermediary']['codeori'] ?></CodEORI>
                    <?php endif; ?>
                </Anagrafica>
            </DatiAnagrafici>
        </TerzoIntermediarioOSoggettoEmittente>
    <?php endif; ?>
</FatturaElettronicaHeader>
