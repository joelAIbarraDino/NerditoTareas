<?php

namespace App\Enums;

enum PaymentMethod:string
{
    case AccountMoney = 'account_money';
    case Ticket = 'ticket';
    case BankTransfer = 'bank_transfer';
    case Atm = 'atm';
    case CreditCard = 'credit_card';
    case DebitCard = 'debit_card';
    case PrepaidCard = 'prepaid_card';
    case DigitalCurrency = 'digital_currency';
    case VoucherCard = 'voucher_card';
    case CryptoTransfer = 'crypto_transfer';
}
