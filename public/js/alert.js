function showSuccessAlert(title = "Success", message) {
  Swal.fire({
    icon: "success",
    title: title,
    text: message,
    showConfirmButton: true,
  });
}

function showConfirmationDelete(data, name) {
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
      )}&action=delete&${data}Name=${encodeURIComponent(name)}`;
    }
  });
}
