document.addEventListener("DOMContentLoaded", () => {
  const likeIcons = document.querySelectorAll(".like-icon");

  likeIcons.forEach((icon) => {
    icon.addEventListener("click", () => {
      const postId = icon.dataset.postId;
      const countSpan = document.getElementById("like-count-" + postId);
      const heart = icon.querySelector(".heart"); // le cœur
      let count = parseInt(countSpan.textContent);

      // Changer le cœur et compteur
      if(icon.classList.contains("liked")){
    count--;
    icon.classList.remove("liked");
    heart.textContent = "🤍";
} else {
    count++;
    icon.classList.add("liked");
    heart.textContent = "❤️";
}
countSpan.textContent = count;

      // Ici tu peux faire un fetch/Ajax pour sauvegarder le like en BDD
    });
  });
});
