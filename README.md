

# Installation
```
composer require amidesfahani/farsi-helper
```

# Usage

## بررسی اعداد و کاراکترهای فارسی
```
FarsiHelper::isPersianAlpha($inputStr);
FarsiHelper::isPersianNum($inputStr);
FarsiHelper::isPersianAlphaNum($inputStr);
```

## تبدیل اعداد فارسی به انگلیسی
```
FarsiHelper::FarsiNumbersToEnglish($codeMelli);

// short version
FarsiHelper::toEnNumber($codeMelli);
```

## جایگزین اعداد عربی با فارسی
```
FarsiHelper::RemoveArabian($codeMelli);
```

## اعتبار سنجی کد ملی
```
FarsiHelper::isMelliCode($codeMelli);
```

## تبدیل «کاف» عربی (ك) به فارسی (ک)
```
FarsiHelper::convertKa($inputStr);
```

## تبدیل «ی» عربی (ي) به فارسی (ی) 
```
FarsiHelper::convertYa($inputStr);
```

## تبدیل اعداد عربی به فارسی
```
FarsiHelper::convertArabicNum($inputStr);
```

## تبدیل اعداد انگلیسی به فارسی
```
FarsiHelper::convertEnglishNum($inputStr);
```

## اصلاح ممیز اعشار فارسی
```
FarsiHelper::convertDecimalSeparator($inputStr);
```

## تنظیم میله‌فاصله اطراف پرانتز، آکولاد و کروشه
```
FarsiHelper::convertParenthesisSpace($inputStr);
```

## تنظیم میله‌فاصله اطراف علامت انتهای جمله (نقطه، سوال و تعجب)
```
FarsiHelper::convertPunctuationSpace($inputStr);
```