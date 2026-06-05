const blogContainer = document.getElementById("blog-container");

// Sample blog data
const blogs = [
    {
        image: "highbroimage/blog1.jpg",
        title: "Sport and Team",
        content: "Sports are more than just physical activity; they embody the spirit of collaboration, discipline, and shared goals. Team sports, in particular, highlight the beauty of working together toward a common purpose. Whether it's basketball, soccer, or cricket, every player contributes their unique strengths to achieve success. The essence of teamwork lies in trust and understanding—knowing when to lead and when to support. This dynamic fosters a sense of belonging and mutual respect, both on and off the field. Through sports, players learn valuable life lessons, such as handling victories with humility and defeats with grace.", link: "#"
    },
    {
        image: "highbroimage/blog2.jpg",
        title: "Academic Excellence",
        content:"Military colleges are institutions that seamlessly blend academic rigor with leadership training, shaping individuals into well-rounded leaders. These colleges prioritize discipline, resilience, and responsibility, instilling values essential for success in both military and civilian life. With a structured environment, students are taught time management and teamwork while pursuing academic excellence. The curriculum often integrates science, technology, engineering, and mathematics (STEM) with physical training, fostering both intellectual and physical development. Leadership is at the core of military college education, with students given roles to lead their peers and solve real-world challenges."
,       link:"#"
    },
    {
        image: "highbroimage/blog1.jpg",
        title: "Sport amd Team",
        content:"Military colleges are institutions that seamlessly blend academic rigor with leadership training, shaping individuals into well-rounded leaders. These colleges prioritize discipline, resilience, and responsibility, instilling values essential for success in both military and civilian life. With a structured environment, students are taught time management and teamwork while pursuing academic excellence. The curriculum often integrates science, technology, engineering, and mathematics (STEM) with physical training, fostering both intellectual and physical development. Leadership is at the core of military college education, with students given roles to lead their peers and solve real-world challenges."
        ,link: "#"
    }
];

// Dynamically generate blog posts
blogs.forEach(blog => {
    const postDiv = document.createElement("div");
    postDiv.classList.add("blog-post");

    postDiv.innerHTML = `
        <img src="${blog.image}" alt="${blog.title}">
        <h2>${blog.title}</h2>
        <p>${blog.content}</p>
         <a href="${blog.link}" class="read-more">Read More</a>
    `;

    blogContainer.appendChild(postDiv);
});
