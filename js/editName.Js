let originalValues = {};

function editName(inputId) {
    const inputElement = document.getElementById(inputId);
    if (inputElement.disabled) {
        originalValues[inputId] = inputElement.value; // Lưu giá trị ban đầu
        inputElement.disabled = false;
        inputElement.focus();
        inputElement.setSelectionRange(inputElement.value.length, inputElement.value.length); // Di chuyển con trỏ về cuối
    }
}

document.addEventListener('click', function(event) {
    const inputElements = document.querySelectorAll('.group-items');
    inputElements.forEach(inputElement => {
        const editPen = inputElement.previousElementSibling?.querySelector('img');
        if (!inputElement.contains(event.target) && (!editPen || !editPen.contains(event.target))) {
            const inputId = inputElement.id;
            if (inputElement.value === originalValues[inputId]) {
                inputElement.value = originalValues[inputId]; // Khôi phục giá trị ban đầu nếu không thay đổi
            }
            inputElement.disabled = true;
        }
    });
});

// let originalValues = {};
// let isEditing = false;

// function editName(inputId) {
//     const inputElement = document.getElementById(inputId);
//     if (inputElement.disabled) {
//         originalValues[inputId] = inputElement.value; // Lưu giá trị ban đầu
//         inputElement.type = inputId === 'edit' ? 'text' : inputElement.type; // Hiển thị giá trị thực cho đổi mật khẩu
//         inputElement.disabled = false;
//         inputElement.focus();
//         inputElement.setSelectionRange(inputElement.value.length, inputElement.value.length); // Di chuyển con trỏ về cuối
//         isEditing = true;
//     }
// }

// document.addEventListener('click', function(event) {
//     const inputElements = document.querySelectorAll('.group-items');
//     inputElements.forEach(inputElement => {
//         const editPen = inputElement.previousElementSibling?.querySelector('img');
//         if (!inputElement.contains(event.target) && (!editPen || !editPen.contains(event.target)) && isEditing) {
//             const inputId = inputElement.id;
//             if (inputId === 'edit') {
//                 inputElement.type = 'password'; // Chuyển thành mật khẩu (dấu chấm)
//                 if (inputElement.value === '') {
//                     inputElement.value = originalValues[inputId]; // Khôi phục giá trị ban đầu nếu không thay đổi
//                 }
//             }
//             inputElement.disabled = true;
//         }
//     });
//     isEditing = false;
// });

