function showSuccessAlert(title = 'Success', message) {
  Swal.fire({
    icon: "success",
    title: title,
    text: message,
    showConfirmButton: true,
  });
}
