 function openModal(element) {
            var modal = document.getElementById("fullscreenModal");
            var modalImg = document.getElementById("modalImage");
            var captionText = document.getElementById("modalCaption");
            modal.style.display = "block";
            modalImg.src = element.src;
            captionText.innerHTML = element.alt;
        }
        
        function closeModal() {
            var modal = document.getElementById("fullscreenModal");
            modal.style.display = "none";
        }