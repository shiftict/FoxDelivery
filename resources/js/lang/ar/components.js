module.exports = {

    groups: {
        addTitle: 'مجموعة جديدة',
        editTitle: 'تعديل المجموعة ',
        permissionTitle: 'صلاحيات المجموعة',
        title: 'مجموعات المستخدمين',
        subTitle: 'ادارة المجموعات ',
        refresh: 'تحديث',
        search: 'البحث في المجموعات ...',
        filter: {
            statusTitle: 'حسب حالة المجموعة ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        fields: {
            name: 'اسم المجموعة',
            description: 'الوصف',
            status: 'الحالة',
            actions: 'الاوامر',
        },
        hint: {
            name: 'اسم المجموعة يجب أن لا يكون مستخدم مسبقا'
        }
    }, users: {

        addTitle: 'مستخدم جديد',
        title: 'مستخدموا النظام',
        editTitle: 'تعديل بيانات المستخدم',
        changePassword: 'تعديل كلمة المرور',
        refresh: 'تحديث',
        fields: {
            name: 'اسم المستخدم',
            email: 'البريد الالكتروني',
            group: 'المجموعة',
            status: 'الحالة',
            stores: 'المخازن',
            password: 'كلمة المرور',
            password_confirmation: 'تأكيد كلمة المرور',
            new_password: 'كلمة المرور الجديدة',
            new_password_confirmation: 'تأكيد كلمة المرور الجديدة',
            actions: 'الاوامر',
        },
        hint: {
            name: 'اسم المستخدم يجب أن لا يكون مستخدم مسبقا',
            email: 'البريد الالكتروني يجب أن لا يكون مستخدم مسبقا',
            password: 'يجب ان تتكون كلمة المرور من ٨ خانات على الأقل',
            password_confirmation: 'يجب ان تكون مطابقة لكلمة المرور  المدخلة',
        },
        permissionTitle: 'صلاحيات المجموعة',
        subTitle: 'ادارة المستخدمين ',
        search: 'البحث في المستخدمين ...',
        filter: {
            statusTitle: 'حسب حالة المستخدمين ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
    },
    module: {
        addTitle: 'موديول جديدة',
        editTitle: 'تعديل الموديول ',
        permissionTitle: 'صلاحيات الموديول',
        title: 'موديول ',
        subTitle: 'ادارة الموديول ',
        refresh: 'تحديث',
        search: 'البحث في الموديول ...',
        fields: {
            name: 'اسم الموديول',
            description: 'الوصف',
            status: 'الحالة',
            actions: 'الاوامر',
        },
        filter: {
            statusTitle: 'حسب حالة المجموعة ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        /* hint: {
             name: 'اسم الموديول يجب أن لا يكون مستخدم مسبقا'
         }*/
    }, permissions: {
        addTitle: 'صلاحية جديدة',
        editTitle: 'تعديل الصلاحية ',
        permissionTitle: 'صلاحيات الموديول',
        title: 'صلاحية ',
        subTitle: 'ادارة الصلاحيات ',
        refresh: 'تحديث',
        search: 'البحث في الصلاحيات ...',
        fields: {
            name: 'اسم الصلاحية',
            sys_name: 'الاسم المختصر',
            status: 'الحالة',
            actions: 'الاوامر',
            module: 'مديول',
            module_uuid: 'مديول',
        },
        filter: {
            statusTitle: 'حسب حالة المجموعة ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        hint: {
            sys_name: 'اسم الصلاحية المختصر يجب أن لا يكون مستخدم مسبقا'
        }
    },
    menus: {
        addTitle: 'قائمة جديدة',
        addItem: 'عنصر جديد',
        editItem: 'تعديل العنصر',
        editTitle: 'تعديل القائمة ',
        title: 'ادارة القوائم',
        subtitle: 'قوائم النظام',
        menueItem: 'عناصر القائمة',
        refresh: 'تحديث',
        tabletitle: 'القوائم',
        search: 'البحث في القوائم ...',
        detailes: 'معلومات القائمة',
        editNameMenu: 'معلومات القائمة',
        filter: {
            statusTitle: 'حسب حالة القائمة ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        fields: {
            name: 'اسم القائمة',
            description: 'الوصف',
            icon: 'الايقونة',
            order: 'الترتيب',
            link: 'الرابط',
            linkType: 'نوع الرابط',
            permissionName: 'اسم الصلاحية',
            parent: 'يتبع الى',
            menu_id: 'القائمة الرئيسية',
            status: 'الحالة',
            actions: 'الاوامر',
        },
        hint: {
            name: 'اسم القائمة مطلوب',
            description: 'وصف القائمة مطلوب',
            icon: 'اسم الايقونة مطلوب',
            order: 'رقم الترتيب مطلوب',
            link: 'الرابط مطلوب ',
            linkType: 'نوع الرابط مطلوب',
            selectLinkType: 'الرجاء اختيار نوع الرابط',
            samePage: 'البقاء في نفس النافذة',
            anotherPage: 'فتح نافذة جديدة',
            permissionName: 'اسم الصلاحية مطلوب',
            menu_id: 'القائمة الرئيسية مطلوبة',
        }
    },
    building: {
        addTitle: 'اضافة مبنى جديد',
        title: 'جميع المباني',
        buildings: 'المباني',
        titleModel: 'مبنى جديد',
        infoBuilding: 'معلومات المبنى',
        floorBuilding: 'طوابق المبنى',
        search: 'البحث في المباني ...',
        filter: {
            statusTitle: 'حسب حالة المبنى ',
            active: 'فعال',
            notActive: 'معطل',
        },
        fields: {
            name: 'اسم المبنى',
            description: 'الوصف',
            code: 'الرمز',
            status: 'الحالة',
            actions: 'الاوامر',
        },
        hint: {
            name: 'اسم المبنى مطلوب',
            description: 'وصف المبنى مطلوب',
            code: 'رمز المبنى مطلوب',
        }
    },
    floor: {
        addTitle: 'اضافة طابق جديد',
        title: 'جميع الطوابق',
        floors: 'الطوابق',
        titleModel: 'طابق جديد',
        infoBuilding: 'معلومات الطابق',
        roomFloor: 'غرف الطابق',
        search: 'البحث في الطوابق ...',
        filter: {
            statusTitle: 'حسب حالة الطابق ',
            active: 'فعال',
            notActive: 'معطل',
        },
        fields: {
            name: 'اسم الطابق',
            description: 'الوصف',
            building: 'يتبع لبناية',
            code: 'الرمز',
            status: 'الحالة',
            actions: 'الاوامر',
        },
        hint: {
            name: 'اسم الطابق مطلوب',
            description: 'وصف الطابق مطلوب',
            code: 'رمز الطابق مطلوب',
        }
    },
    room: {
        addTitle: 'اضافة غرفة جديدة',
        title: 'جميع الغرف',
        room: 'الغرف',
        titleModel: 'غرفة جديدة',
        inforoom: 'معلومات الغرفة',
        roomChilde: 'اجزاء الغرف',
        roomType: 'نوع الغرفة',
        search: 'البحث في الغرف ...',
        filter: {
            statusTitle: 'حسب حالة الغرفة ',
            active: 'فعال',
            notActive: 'معطل',
        },
        fields: {
            name: 'اسم الغرفة',
            description: 'الوصف',
            code: 'الرمز',
            floor: 'يتبع لطابق',
            room: 'يتبع لغرفة',
            status: 'الحالة',
            actions: 'الاوامر',
            parties: 'الدائرة',
        },
        hint: {
            name: 'اسم الغرفة مطلوب',
            description: 'وصف المبنى مطلوب',
            code: 'رمز المبنى مطلوب',
        }
    },
    parties: {
        addTitle: 'الجهات الطالبة',
        addItem: 'اضافة جهة جديدة',
        editItem: 'تعديل الجهة الطلبة',
        title: 'ادارة الجهات',
        refresh: 'تحديث',
        tabletitle: 'الجهات الطالبة',
        titleModel: 'اضافة جهة جديدة',
        search: 'البحث في الجهات ...',
        filter: {
            statusTitle: 'حسب حالة الجهة ',
            active: 'فعال',
            notActive: 'معطل',
        },
        fields: {
            name: 'اسم الجهة الطلبة',
            status: 'الحالة',
            actions: 'الاوامر',
        },
        hint: {
            name: 'اسم الجهة الطلبة مطلوب',
        },
        requisitions:{
            name:'طلبات الصرف الواردة',
            code:'الكود',
            parties:'الجهة الطالبة',
            decision_maker:'الامر بالصرف',
            date:'تاريخ الطلب',
            create:'انشاء طلب',
            title:'عنوان الطلب',
            addItem: 'انشاء طلب',
            editItem: 'تعديل الطلب ',
            refresh: 'تحديث',
            search: 'البحث في الطلابات ...',
            filter: {
                statusTitle: 'حسب حالة المبنى ',
                active: 'فعال',
                notActive: 'معطل',
            },
            validation:{
                title:'العنوان مطلوب',
                parties:'الجهة الطالبة مطلوبة',
                decision_maker:'الامر بالصؤف  مطلوب'
            },
            actions: 'الاوامر',
            stores: 'المخازن',
            rootCategory: 'التصنيفات الرئيسية',

        }
    },
    supplier: {
        addTitle: 'اضافة مبنى جديد',
        title: 'جميع المزودون',
        buildings: 'المباني',
        titleModel: 'اضافة مزود جديد',
        infoSupplier: 'معلومات المزود',
        search: 'البحث في المزودين ...',
        closebutton: 'اغلاق',
        services: 'الخدمات',
        filter: {
            statusTitle: 'حسب حالة المبنى ',
            active: 'فعال',
            notActive: 'معطل',
        },
        fields: {
            name: 'اسم المزود',
            sid: 'الرقم التسلسلي',
            email: 'البريد الالكتروني',
            address: 'العنوان',
            telephone: 'الهاتف',
            mobile: 'الجوال',
            fax: 'الفاكس',
            status: 'الحالة',
            actions: 'الاوامر',
            service_type: 'الخدمات',
        },
        hint: {
            name: 'اسم المزود مطلوب',
            email: 'البريد الالكتروني للمزود مطلوب',
            sid: 'رمز المزود مطلوب',
        }
    },
    withdraw: {
        addTitle: 'اضافة طلب جديد',
        title: 'جميع الطلبات',
        search: 'البحث في الطلبات ...',
        closebutton: 'اغلاق',
        services: 'الطلبات',
        stors: 'المخازن',
        consumables: 'مستهلكات',
        assetsArea: 'عهد مكان',
        assetsPerson: 'عهد شخصية',
        dropdownTitle: 'اختار نوع الصرف',
        filter: {
            statusTitle: 'حسب حالة الطلبية ',
            complete: 'مكتملة',
            partialExchange: 'صرف جزئي',
            fullExchange: 'صرف كامل',
            reject: 'مرفوضة',
            constants: 'ثوابت',
            consumables: 'مستهلكات',
            buttons: 'فلتر الاصناف',
        },
        fields: {
            name: 'اسم الطلبية',
            sid: 'الرقم التسلسلي',
            email: 'البريد الالكتروني',
            address: 'العنوان',
            telephone: 'الهاتف',
            mobile: 'الجوال',
            fax: 'الفاكس',
            status: 'الحالة',
            actions: 'الاوامر',
            service_type: 'الخدمات',
        },
        hint: {
            name: 'اسم المزود مطلوب',
            email: 'البريد الالكتروني للمزود مطلوب',
            sid: 'رمز المزود مطلوب',
        }
    },
    vouchers: {
        addTitle: 'اضافة مبنى جديد',
        title: 'جميع الخدمات',
        titleModel: 'اضافة خدمة جديدة',
        infoSupplierService: 'معلومات الخدمة',
        search: 'البحث في الخدمات ...',
        closebutton: 'اغلاق',
        services: 'الخدمات',
        quantityAbove: 'الكمية المطلوبة يجب ان تكون صحيحة',
        filter: {
            statusTitle: 'حسب حالة الخدمة ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        fields: {
            name: 'اسم الخدمة',
            status: 'الحالة',
            actions: 'الاوامر',
        },
        hint: {
            name: 'اسم الخدمة مطلوب',
        },
        errorMessage: {
            dateRequired: 'تاريخ صرف الطلبية مطلوب',
            errorMessage: 'تم اضافة الاصناف للصرف',
        }
    },
    SuppliersServices: {
        addTitle: 'اضافة مبنى جديد',
        title: 'جميع الخدمات',
        titleModel: 'اضافة خدمة جديدة',
        infoSupplierService: 'معلومات الخدمة',
        search: 'البحث في الخدمات ...',
        closebutton: 'اغلاق',
        services: 'الخدمات',
        filter: {
            statusTitle: 'حسب حالة الخدمة ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        fields: {
            name: 'اسم الخدمة',
            status: 'الحالة',
            actions: 'الاوامر',
        },
        hint: {
            name: 'اسم الخدمة مطلوب',
        }
    },
    TwoFactor: {
    }, TwoFactor: {
        title: 'توثيق الحساب',
        subtitle: 'أضف أمانًا إضافيًا إلى حسابك باستخدام المصادقة الثنائية.',
        subtitle1: ' لم تقم بتمكين المصادقة الثنائية.',
        subtitle2: 'لقد قمت بتمكين المصادقة الثنائية.',
        subtitle3: 'عند تمكين المصادقة الثنائية ، ستتم مطالبتك برمز مميز آمن وعشوائي أثناء المصادقة. يمكنك استرداد هذا الرمز المميز من تطبيق Google Authenticator بهاتفك.',
        subtitle4: 'تم تمكين المصادقة الثنائية الآن. امسح رمز الاستجابة السريعة التالي ضوئيًا باستخدام تطبيق المصادقة على هاتفك.',
        subtitle5: 'قم بتخزين رموز الاسترداد هذه في مدير كلمات مرور آمن. يمكن استخدامها لاستعادة الوصول إلى حسابك في حالة فقد جهاز المصادقة الثنائية الخاص بك.',
        subtitle6: 'إظهار رموز الاسترداد',
        subtitle7: 'إعادة إنشاء رموز الاسترداد',
        enable: ' تمكين',
        disable: ' الغاء التمكين',
    },
    datatable: {
        dropdownTitle: 'اختر الامر المناسب'
    },
    menu: {
        title: 'الذهاب إلى'
    },
    buttons: {
        close: 'إغلاق',
        save: 'حفظ البيانات',
        edit: 'تحديث البيانات',
        resets: 'تراجع',
        extract: 'استخراج'
    },
    TwoFactor: {
        title: 'توثيق الحساب',
        subtitle: 'أضف أمانًا إضافيًا إلى حسابك باستخدام المصادقة الثنائية.',
        subtitle1: ' لم تقم بتمكين المصادقة الثنائية.',
        subtitle2: 'لقد قمت بتمكين المصادقة الثنائية.',
        subtitle3: 'عند تمكين المصادقة الثنائية ، ستتم مطالبتك برمز مميز آمن وعشوائي أثناء المصادقة. يمكنك استرداد هذا الرمز المميز من تطبيق Google Authenticator بهاتفك.',
        subtitle4: 'تم تمكين المصادقة الثنائية الآن. امسح رمز الاستجابة السريعة التالي ضوئيًا باستخدام تطبيق المصادقة على هاتفك.',
        subtitle5: 'قم بتخزين رموز الاسترداد هذه في مدير كلمات مرور آمن. يمكن استخدامها لاستعادة الوصول إلى حسابك في حالة فقد جهاز المصادقة الثنائية الخاص بك.',
        subtitle6: 'إظهار رموز الاسترداد',
        subtitle7: 'إعادة إنشاء رموز الاسترداد',
        enable: ' تمكين',
        disable: ' الغاء التمكين'
    },
    stores: {
        addTitle: 'مخزن جديدة',
        editTitle: 'تعديل المخزن ',
        title: 'المخازن ',
        subTitle: 'ادارة المخازن ',
        refresh: 'تحديث',
        search: 'البحث في المخازن ...',
        fields: {
            name: 'اسم المخازن',
            description: 'الوصف',
            status: 'الحالة',
            code: 'الكود',
            actions: 'الاوامر',
        },
        filter: {
            statusTitle: 'حسب حالة المخازن ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        hint: {
            code: ' الكود يجب أن لا يكون مستخدم مسبقا'
        }
    }, unit: {
        addTitle: 'وحدة جديدة',
        editTitle: 'تعديل الوحدة ',
        title: 'الوحدات ',
        subTitle: 'ادارة الوحدات ',
        refresh: 'تحديث',
        search: 'البحث في الوحدات ...',
        fields: {
            name: 'اسم الوحدة',
            description: 'الوصف',
            status: 'الحالة',
            code: 'الكود',
            actions: 'الاوامر',
        },
        filter: {
            statusTitle: 'حسب حالة الوحدات ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        hint: {
            name: ' الاسم يجب أن لا يكون مستخدم مسبقا'
        }
    }, item: {
        addTitle: 'صنف جديدة',
        editTitle: 'تعديل صنف ',
        title: 'الاصناف ',
        subTitle: 'ادارة الاصناف ',
        refresh: 'تحديث',
        search: 'البحث في الاصناف ...',
        fields: {
            name: 'اسم الصنف',
            description: 'الوصف',
            status: 'الحالة',
            code: 'الكود',
            price_average: 'متوسط السعر',
            alert_quantity: 'تحذير الكمية',
            actions: 'الاوامر',
            unit_uuid: 'الوحدة',
            store_uuid: 'المخزن',
            category_uuid: 'التصنيف',
            start_quantity: 'القيمة الافتتاحية في المخزن',
        },
        filter: {
            statusTitle: 'حسب حالة الاصناف ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        hint: {
            name: ' الاسم يجب أن لا يكون مستخدم مسبقا'
        }
    }, agenda: {
        addTitle: 'اجندة جديدة',
        editTitle: 'تعديل اجندة ',
        title: 'الاجندات ',
        subTitle: 'ادارة الاجندات ',
        refresh: 'تحديث',
        search: 'البحث في الاجندات ...',
        fields: {
            year: ' السنة الدراسية',
            start_date: ' تاريخ بداية السنة الدراسية',
            end_date: 'تاريخ نهاية السنة الدراسية ',
            first_quarter_date: ' بداية الفصل الاول',
            second_quarter_date: 'بداية الفصل الثاني  ',
            third_quarter_date: 'بداية الفصل الثالث ',
            description: 'الوصف',
        },
        filter: {
            statusTitle: 'حسب حالة الاصناف ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        hint: {
            year: ' السنة يجب أن لا يكون مستخدم مسبقا'
        }
    },


    category: {
        addTitle: 'تصنيف جديدة',
        editTitle: 'تعديل التصنيف ',
        title: 'التصنيفات ',
        main_title: 'التصنيفات  الرئيسية',
        sub_title: 'التصنيفات  الفرعية',
        subTitle: 'ادارة التصنيفات ',
        refresh: 'تحديث',
        search: 'البحث في التصنيفات ...',
        titleParent: 'التصنيف الاب  ...',
        fields: {
            name: 'اسم التصنيف',
            description: 'الوصف',
            status: 'الحالة',
            parent_uuid: 'الاب',
            store_uuid: 'المخزن',
            code: 'الكود',
            actions: 'الاوامر',
        },
        filter: {
            statusTitle: 'حسب حالة التصنيف ',
            active: 'فعالة',
            notActive: 'معطلة',
        },
        hint: {
            code: ' الكود يجب أن لا يكون مستخدم مسبقا'
        }
    },
    datatable: {
        dropdownTitle: 'اختر الامر المناسب'
    },
    menu: {
        title: 'الذهاب إلى'
    },
    buttons: {
        close: 'إغلاق',
        save: 'حفظ البيانات',
        edit: 'تحديث البيانات',
        resets: 'تراجع',
        extract: 'استخراج',
        filter: 'فلتر',
        options: 'الخيارات',
        search: 'بحث',
        refresh: 'تحديث',
    },
    site: {
        index: 'الرئيسية'
    },
}
