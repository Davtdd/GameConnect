document.addEventListener("DOMContentLoaded", () => {
  const likeIcons = document.querySelectorAll(".like-icon");

  likeIcons.forEach((icon) => {
    icon.addEventListener("click", () => {
      const postId = icon.dataset.postId;
      const countSpan = document.getElementById("like-count-" + postId);
      let count = parseInt(countSpan.textContent);

      // Changer le cœur et compteur
      if (icon.classList.contains("liked")) {
        count--; // retirer like
        icon.classList.remove("liked");
        icon.innerHTML = `🤍 <span class="like-count" id="like-count-${postId}">${count}</span>`;
      } else {
        count++; // ajouter like
        icon.classList.add("liked");
        icon.innerHTML = `❤️ <span class="like-count" id="like-count-${postId}">${count}</span>`;
      }

      // Ici tu peux faire un fetch/Ajax pour sauvegarder le like en BDD
    });
  });
});
