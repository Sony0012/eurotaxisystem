// demo.tsx

import { GradientCard } from "@/components/ui/gradient-card"; // Adjust path as needed

// Data for the cards to demonstrate reusability
const cardData = [
  {
    badgeText: "Open / Invite-priority",
    badgeColor: "#F59E0B", // Amber
    title: "Companies",
    description: "Build teams of highly motivated tech-professionals across the globe, with projects across all industries.",
    ctaText: "Start hiring",
    ctaHref: "#",
    imageUrl: "https://www.thiings.co/_next/image?url=https%3A%2F%2Flftz25oez4aqbxpq.public.blob.vercel-storage.com%2Fimage-CVv0qK2DYZbOAQP2LboVFgQGt0UMfB.png&w=320&q=75", // Replace with your image URL
    gradient: "orange" as const,
  },
  {
    badgeText: "Open for applications",
    badgeColor: "#4B5563", // Gray
    title: "Builders",
    description: "Work on your own terms in a motivating and healthy environment. You will earn TMW-tokens too!",
    ctaText: "Apply now",
    ctaHref: "#",
    imageUrl: "https://www.thiings.co/_next/image?url=https%3A%2F%2Flftz25oez4aqbxpq.public.blob.vercel-storage.com%2Fimage-5i9EDsbgEZk9k7NBeKt3ImNXkx0F66.png&w=320&q=75", // Replace with your image URL
    gradient: "gray" as const,
  },
  {
    badgeText: "Invite only",
    badgeColor: "#8B5CF6", // Purple
    title: "Scouts",
    description: "As a scout you will utilize your network to refer new members and companies to earn ownership in form of TMW-tokens.",
    ctaText: "Request invite",
    ctaHref: "#",
    imageUrl: "https://www.thiings.co/_next/image?url=https%3A%2F%2Flftz25oez4aqbxpq.public.blob.vercel-storage.com%2Fimage-5WJZLkaCfLUnCYpgNz89tPx5C4KYgJ.png&w=320&q=75", // Replace with your image URL
    gradient: "purple" as const,
  },
  {
    badgeText: "Invite only",
    badgeColor: "#10B981", // Green
    title: "Partners",
    description: "As a partner you can offer direct access to the Teamway society to your portfolio companies, community or customers.",
    ctaText: "Get in touch",
    ctaHref: "#",
    imageUrl: "https://www.thiings.co/_next/image?url=https%3A%2F%2Flftz25oez4aqbxpq.public.blob.vercel-storage.com%2Fimage-Q24CTBwBqnBrGujxuykBW9GfOYTdeE.png&w=320&q=75", // Replace with your image URL
    gradient: "green" as const,
  },
];

const GradientCardDemo = () => {
  return (
    <div className="p-4 sm:p-8">
      <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:gap-10">
        {cardData.map((card, index) => (
          <GradientCard
            key={index}
            badgeText={card.badgeText}
            badgeColor={card.badgeColor}
            title={card.title}
            description={card.description}
            ctaText={card.ctaText}
            ctaHref={card.ctaHref}
            imageUrl={card.imageUrl}
            gradient={card.gradient}
          />
        ))}
      </div>
    </div>
  );
};

export default GradientCardDemo;
