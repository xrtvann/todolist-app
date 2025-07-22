function showSuccessAlert(title = "Success", message) {
  Swal.fire({
    icon: "success",
    title: title,
    text: message,
    showConfirmButton: true,
  });
}

function showConfirmationDelete(data, id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: `Do you want to delete ${data} "${name}"?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#ef4444",
    confirmButtonText: "Yes, Delete it!",
    cancelButtonText: "No, Cancel!",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `?page=${encodeURIComponent(
        data
      )}&action=delete&id=${encodeURIComponent(id)}`;
    }
  });
}

showErrorAlert = (title, message) => {
  Swal.fire({
    icon: "error",
    title: title,
    text: message,
    confirmButtonColor: "#ef4444",
    confirmButtonText: "Try Again",
  });
};

showInfoAlert = (title, message) => {
  Swal.fire({
    icon: "info",
    title: title,
    text: message,
    confirmButtonColor: "#3b82f6",
    confirmButtonText: "OK",
  });
};
