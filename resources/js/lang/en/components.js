module.exports = {
    category : {
        addTitle: 'Add New Category',
      fields:{
        nameAr: 'Arabic Name',
        nameEn: 'English Name',
        category: 'Category',
        actions: 'Actions',
      }
    },
    page : {
        prop: 'Page Properties',
        addTitle: 'Page Content',
        fields:{
            title: 'Title',
            url: 'URL',
            body: 'Body',
            language: 'Language',
            category: 'Category',
            tags: 'key Words',
            status: 'Status',
            actions: 'Actions',
        },
        placeholder:{
            language: 'Select Language ...',
            category: 'Select Category ...',
        },
        notes:{
            tags: 'Please press (Enter) after each word',
        }
    },
    post : {
        prop: 'Post Properties',
        addTitle: 'Post Content',
        fields:{
            title: 'Title',
            description: 'Description',
            url: 'URL',
            date: 'Date',
            order: 'Order',
            body: 'Body',
            language: 'Language',
            category: 'Category',
            image: 'Image',
            tags: 'Key Words',
            status: 'Status',
            actions: 'Actions',
        },
        placeholder:{
            language: 'Select Language ...',
            category: 'Select Category ...',
            date: 'Select Publishing Date',
        },
        notes:{
            tags: 'Please press (Enter) after each word',
            image: 'if you keep it empty, the system will put default image',
            order: 'Enter (-1) to be latest one',
        }
    },
    imagePicker : {
        title: 'File Uploader',
        select: 'Select Image',
        upload: 'New Image',
    },
}